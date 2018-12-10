<?php

namespace PhalconExt\Mvc\Controllers;


use Phalcon\Mvc\Model\Criteria;
use PhalconExt\Mvc\Controllers\Grid\ButtonConfig;
use PhalconExt\Mvc\Controllers\Grid\FieldConfig;
use PhalconExt\Mvc\Controllers\Grid\RowMapConfig;
use PhalconExt\Mvc\Models\Model;
use PhalconExt\Services\Page;
use PhalconExt\Services\Request;
use PhalconExt\Services\Response;
use PhalconExt\Utils\ArrayUtil;

class GridController extends Controller
{
    protected $modelClass;
    protected $select = ['*'];
    protected $searchColumns = [];
    protected $showColumns = [];
    protected $orderColumns = [];
    protected $viewColumns = [];
    protected $editColumns = [];
    /**
     * @var FieldConfig[]
     */
    protected $fieldConfigs = [];

    /**
     * @var ButtonConfig[]
     */
    protected $columnButtonConfigs = [];
    /**
     * @var ButtonConfig[]
     */
    protected $rowButtonConfigs = [];

    protected $actionsWidth = 200;

    /**
     * @var Model
     */
    protected $model;
    /**
     * @var Criteria
     */
    protected $query;
    protected $defaultOrder;
    protected $pageSize = 10;


    public function initialize()
    {
        $this->model = new $this->modelClass;
        $this->query = $this->model->query();
        $this->buildConfig();
    }

    private function buildConfig()
    {

        $this->buildFiledConfigs();
        $this->afterBuildFieldConfigs();
        $this->beforeBuildButtonConfigs();
        $this->buildButtonConfigs();
    }


    protected function buildFiledConfigs()
    {
    }

    private function afterBuildFieldConfigs()
    {
        $columnLabels = $this->model->columnLabels();
        foreach ([$this->showColumns, $this->editColumns, $this->searchColumns, $this->viewColumns] as $columns) {
            foreach ($columns as $column) {
                $name = isset($columnLabels[snake_case($column)]) ? $columnLabels[snake_case($column)] : $column;
                $this->fieldConfigs[$column] = isset($this->fieldConfigs[$column]) ? $this->fieldConfigs[$column] : new FieldConfig($name);
            }
        }
        $this->fieldConfigs = ArrayUtil::snakeCaseKeys($this->fieldConfigs);
    }

    private function beforeBuildButtonConfigs()
    {
        $this->columnButtonConfigs = [
            ButtonConfig::create('搜索', 'search'),
        ];
        $this->rowButtonConfigs = [
            ButtonConfig::create('查看', 'view'),
            ButtonConfig::create('编辑', 'edit'),
            ButtonConfig::create('删除', 'remove'),
        ];
    }

    /**
     * @return void
     */
    protected function buildButtonConfigs()
    {
    }

    /**
     * 组装row
     * @param $row
     * @param $type
     * @return
     */
    protected function buildRow($row, $type)
    {
        return $row;
    }


    /**
     * 对数据进行format
     * @param array $rows
     * @return array
     */
    protected function formatRows(array $rows)
    {
        $data = [];
        $rowMaps = $this->rowMaps($rows);
        $rowActions = $this->rowActions($rows);
        foreach ($rows as $row) {
            foreach ($rowMaps as $rowMap) {
                $mapId = $row[snake_case($rowMap->getKey())];
                $row[snake_case($rowMap->getColumn())] = ArrayUtil::get($rowMap->getListData(), $mapId, '');
            }
            foreach ($row as $key => $value) {
                $fieldKey = snake_case($key);
                if (isset($this->fieldConfigs[$fieldKey])) {
                    $fieldConfig = $this->fieldConfigs[$fieldKey];
                    switch ($fieldConfig->getType()) {
                        case FieldConfig::TYPE_SELECT:
                            $value = ArrayUtil::get($fieldConfig->getListData(true), $value, '');
                            break;
                    }
                }
                $row[$key] = $value;
            }
            $row['$actions'] = isset($rowActions) ? $rowActions[$row['id']] : [];
            $row = $this->buildRow($row, 'data');
            $data[] = $row;
        }
        return $data;
    }

    /**
     * @param $rows
     * @return RowMapConfig[]
     */
    protected function rowMaps(array $rows)
    {
        return [];
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function rowActions(array $rows)
    {
        return array_map(function ($var) {
            $actions = [];
            foreach ($this->rowButtonConfigs as $buttonConfig) {
                $actions[] = $buttonConfig->getKey();
            }
            return $actions;
        }, array_column($rows, null, 'id'));
    }

    /**
     * 更新查询构造器
     * @param Criteria $query
     */
    protected function updateQuery(Criteria $query)
    {
        $search = Request::post('search');
        $searchBasic = isset($search['basic']) ? $search['basic'] : 'basic';
        foreach ($searchBasic as $key => $value) {
            if ($value === '') {
                continue;
            }
            $snakeKey = snake_case($key);
            $config = $this->fieldConfigs[$snakeKey];
            if ($config->getType() == FieldConfig::TYPE_DATETIME) {
                if (!empty($value[0])) {
                    $startTime = date('Y-m-d H:i:s', strtotime($value[0]));
                    $query->andWhere("{$this->db->escapeString($startTime)} <= {$this->modelClass}.{$snakeKey}");
                }
                if (!empty($value[1])) {
                    $endTime = date('Y-m-d H:i:s', strtotime($value[1]));
                    $query->andWhere("{$this->modelClass}.{$snakeKey} <= {$this->db->escapeString($endTime)}");
                }
                continue;
            }
            $operator = $config->getSearchOperator();
            switch ($config->getSearchOperator()) {
                case FieldConfig::SEARCH_OPERATOR_LIKE:
                    $value = "%{$value}%";
                    break;
                case FieldConfig::SEARCH_OPERATOR_LIKE_LEFT:
                    $operator = 'like';
                    $value = "{$value}%";
                    break;
                case FieldConfig::SEARCH_OPERATOR_LIKE_RIGHT:
                    $operator = 'like';
                    $value = "%{$value}";
                    break;
                case FieldConfig::SEARCH_OPERATOR_IN:
                    $value = is_array($value) ? $value : [$value];
                    break;
                case FieldConfig::SEARCH_OPERATOR_EQUAL:
                    break;
            }
            $value = $this->db->escapeString($value);
            $query->andWhere("{$this->modelClass}.{$snakeKey} $operator {$value}");
        }
        $order = array_get($search, 'order');
        if ($order) {
            list($key, $order) = explode(' ', $order);
            $key = snake_case($key);
            if (isset($this->model->columnLabels()[$key]) && in_array($order, ['asc', 'desc'])) {
                $query->orderBy(implode(' ', [$key, $order]));
            }
        } elseif ($this->defaultOrder) {
            $query->orderBy($this->defaultOrder);
        }
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function configAction()
    {
        $data = [
            'showColumns' => ArrayUtil::cameCaseValues($this->showColumns),
            'editColumns' => ArrayUtil::cameCaseValues($this->editColumns),
            'viewColumns' => ArrayUtil::cameCaseValues($this->viewColumns),
            'searchColumns' => ArrayUtil::cameCaseValues($this->searchColumns),
            'orderColumns' => ArrayUtil::cameCaseValues($this->orderColumns),
            'fieldConfigs' => array_map(function ($var) {
                /**
                 * @var $var FieldConfig
                 */
                return $var->toArray();
            }, $this->fieldConfigs),
            'rowButtonConfigs' => array_map(function ($var) {
                /**
                 * @var $var ButtonConfig
                 */
                return $var->toArray();
            }, $this->rowButtonConfigs),
            'columnButtonConfigs' => array_map(function ($var) {
                /**
                 * @var $var ButtonConfig
                 */
                return $var->toArray();
            }, $this->columnButtonConfigs),
            'actionsWidth' => $this->actionsWidth,
        ];
        return Response::success($data);
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function dataAction()
    {
        $this->updateQuery($this->query);
        $page = Request::post('page', 1);
        $data = Page::result($this->query, $this->pageSize, $page, function ($rows) {
            return $this->formatRows($rows);
        });
        return Response::success($data);
    }


    /**
     * excel导出
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function exportAction()
    {
        $this->updateQuery($this->query);
        $rows = $this->query->execute()->toArray();
        $data = $this->formatRows($rows);
        $headers = [];
        /**
         * @var $fieldConfigs FieldConfig[]
         */
        $fieldConfigs = ArrayUtil::snakeCaseKeys($this->fieldConfigs);
        $showColumns = ArrayUtil::snakeCaseValues($this->showColumns);
        foreach ($showColumns as $showColumn) {
            $headers[] = $fieldConfigs[$showColumn]->getName();
        }
        $raw = implode("\t", $headers) . "\n";
        $data = ArrayUtil::snakeCaseKeys($data);
        foreach ($data as $row) {
            $items = [];
            foreach ($showColumns as $showColumn) {
                $cube = isset($row[$showColumn]) ? $row[$showColumn] : '';
                $cube = is_numeric($cube) && strlen($cube) > 10 ? '`' . $cube : $cube;
                $items[] = $cube;
            }
            $raw .= implode("\t", $items) . "\n";
        }
        $raw = iconv('UTF-8', 'GB18030', $raw);
        return Response::success([
            'download' => base64_encode($raw),
        ]);
    }

    /**
     * @return mixed
     * @throws \PhalconExt\Exceptions\ModelNotFoundException
     */
    public function formAction()
    {
        $id = Request::post('id');
        $model = $this->model::findOneOrFail($id)->toArray();
        $model = $this->buildRow($model, 'form');
        $data = [
            'id' => $id,
            FieldConfig::SCOPE_BASIC => [],
            FieldConfig::SCOPE_EXTRA => [],
        ];
        foreach ($this->editColumns as $column) {
            $config = $this->fieldConfigs[$column];
            if ($config->getScope() == FieldConfig::SCOPE_BASIC) {
                $data[$config->getScope()][$column] = $model[snake_case($column)];
            }
        }
        foreach ($data as $k => $v) {
            $data[$k] = empty($v) ? new \stdClass() : $v;
        }
        return Response::success($data);
    }

    /**
     * view页面数据
     * @throws \PhalconExt\Exceptions\ModelNotFoundException
     */
    public function viewAction()
    {
        $id = Request::post('id');
        $model = $this->model::findOneOrFail($id)->toArray();
        $model = $this->buildRow($model, 'view');
        $data = [
            'id' => $id,
            FieldConfig::SCOPE_BASIC => [],
            FieldConfig::SCOPE_EXTRA => [],
        ];
        foreach ($this->viewColumns as $column) {
            $config = $this->fieldConfigs[$column];
            if ($config->getScope() == FieldConfig::SCOPE_BASIC) {
                $data[$config->getScope()][$column] = $model[snake_case($column)];
            }
        }
        foreach ($data as $k => $v) {
            $data[$k] = empty($v) ? new \stdClass() : $v;
        }
        return Response::success($data);
    }

    /**
     * @return \Phalcon\Http\Response
     * @throws \PhalconExt\Exceptions\ModelNotFoundException
     * @throws \PhalconExt\Exceptions\ModelNotSaveException
     */
    public function saveAction()
    {
        $id = Request::post('id');
        $data = Request::post('basic');
        if ($id) {
            $model = $this->model::findOneOrFail($id);
            foreach ($data as $key => $value) {
                $model[snake_case($key)] = $value;
            }
            $model->updateOrFail();
        } else {
            /**
             * @var $model Model
             */
            $model = new $this->modelClass;
            foreach ($data as $key => $value) {
                $model[snake_case($key)] = $value;
            }
            $model->createOrFail();
        }
        return Response::success();
    }


    /**
     * @return \Phalcon\Http\Response
     */
    public function removeAction()
    {
        return Response::success();
    }
}