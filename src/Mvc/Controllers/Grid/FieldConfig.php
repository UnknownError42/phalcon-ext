<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/9/4
 * Time: 上午11:08
 */

namespace PhalconExt\Mvc\Controllers\Grid;

use PhalconExt\Utils\Arr;

class FieldConfig
{
    const TYPE_TEXT = 'text';
    const TYPE_SELECT = 'select';
    const TYPE_IMAGE = 'image';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';

    const SEARCH_OPERATOR_EQUAL = '=';
    const SEARCH_OPERATOR_LIKE = 'like';
    const SEARCH_OPERATOR_LIKE_LEFT = 'like%';
    const SEARCH_OPERATOR_LIKE_RIGHT = '%like';
    const SEARCH_OPERATOR_IN = 'in';

    const SCOPE_BASIC = 'basic';
    const SCOPE_EXTRA = 'extra';

    protected $name;
    protected $type;
    protected $searchOperator;
    protected $listData;
    protected $scope;
    protected $width;

    /**
     * GridFieldConfig constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = camel_case($name);
        $this->type = self::TYPE_TEXT;
        $this->searchOperator = self::SEARCH_OPERATOR_EQUAL;
        $this->listData = [];
        $this->scope = self::SCOPE_BASIC;
    }

    public static function create($name)
    {
        return new static($name);
    }

    /**
     * @param array $listData
     * @return array
     */
    protected function formatListData(array $listData)
    {
        if (Arr::isAssoc($listData) || ($listData && !is_array($listData[0]))) {
            $tempListData = [];
            foreach ($listData as $key => $value) {
                $tempListData[] = [
                    'key' => strval($key),
                    'value' => $value,
                ];
            }
            $listData = $tempListData;
        }
        return $listData;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'searchOperator' => $this->searchOperator,
            'listData' => $this->listData,
            'scope' => $this->scope,
            'width' => $this->width,
        ];
    }

    /**
     * @param string $name
     * @return FieldConfig
     */
    public function setName(string $name): FieldConfig
    {
        $this->name = camel_case($name);
        return $this;
    }

    /**
     * @param string $type
     * @return FieldConfig
     */
    public function setType(string $type): FieldConfig
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $searchOperator
     * @return FieldConfig
     */
    public function setSearchOperator(string $searchOperator): FieldConfig
    {
        $this->searchOperator = $searchOperator;
        return $this;
    }

    /**
     * @param array $listData
     * @return FieldConfig
     */
    public function setListData(array $listData): FieldConfig
    {
        $this->listData = $this->formatListData($listData);
        return $this;
    }

    /**
     * @param string $scope
     * @return FieldConfig
     */
    public function setScope(string $scope): FieldConfig
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @param mixed $width
     * @return FieldConfig
     */
    public function setWidth($width): FieldConfig
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSearchOperator(): string
    {
        return $this->searchOperator;
    }

    /**
     * @param bool $assoc
     * @return array
     */
    public function getListData($assoc = false): array
    {
        return $assoc ? array_column($this->listData, 'value', 'key') : $this->listData;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }
}