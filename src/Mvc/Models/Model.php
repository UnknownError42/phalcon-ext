<?php

namespace PhalconExt\Mvc\Models;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use PhalconExt\Exceptions\ModelNotFoundException;
use PhalconExt\Exceptions\ModelNotSaveException;

/**
 * Class Model
 */
abstract class Model extends \Phalcon\Mvc\Model implements IteratorAggregate, ArrayAccess
{
    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this);
    }

    /**
     * Returns whether there is an element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param mixed $offset the offset to check on
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    /**
     * Returns the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to retrieve element.
     * @return mixed the element at the offset, null if no element is found at the offset
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Sets the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to set element
     * @param mixed $item the element value
     */
    public function offsetSet($offset, $item)
    {
        $this->$offset = $item;
    }

    /**
     * Unsets the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param mixed $offset the offset to unset element
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    /**
     * 列名
     * @return array
     */
    public function columnLabels()
    {
        return [];
    }

    /**
     * 必填列
     * @return array
     */
    public function requiredColumns()
    {
        return array();
    }

    /**
     * 获取单个
     * @param mixed $data
     * @param mixed $columns
     * @param bool $forUpdate
     * @return bool|\Phalcon\Mvc\Model\Row|$this
     */
    public static function findOne($data, $columns = null, $forUpdate = false)
    {
        $model = null;
        $query = self::buildSimpleQuery($data, $columns, $forUpdate);
        $model = $query->execute()->getFirst();
        return $model ? $model : false;
    }

    /**
     * 获取单个或失败
     * @param mixed $data
     * @param null $columns
     * @param bool $forUpdate
     * @return bool|\Phalcon\Mvc\Model\Row|$this
     * @throws ModelNotFoundException
     */
    public static function findOneOrFail($data, $columns = null, $forUpdate = false)
    {
        $model = self::findOne($data, $columns, $forUpdate);
        if (!$model) {
            throw new ModelNotFoundException(self::class . ' Not Found');
        }
        return $model;
    }

    /**
     * @param mixed $data
     * @param mixed $columns
     * @param bool $forUpdate
     * @return null|\Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function findAll($data, $columns = null, $forUpdate = false)
    {
        $result = null;
        $query = self::buildSimpleQuery($data, $columns, $forUpdate);
        $result = $query->execute();
        return $result;
    }

    /**
     * @param $id
     * @param $data
     * @param bool $useRaw
     * @return int
     */
    public static function updateOneOrFail($id, $data, $useRaw = false)
    {
        $model = new static();
        $db = $model->getWriteConnection();
        $sets = [];
        foreach ($data as $key => $value) {
            $value = $useRaw ? $value : $db->escapeString($value);
            $sets[] = $db->escapeIdentifier($key) . '=' . $value;
        }
        $setsString = implode(',', $sets);
        $table = $model->getSource();
        $id = $db->escapeString($id);
        $sql = "UPDATE {$table} SET {$setsString} WHERE id = {$id}";
        $db->execute($sql);
        $affectedRows = $db->affectedRows();
        return $affectedRows;
    }

    /**
     * @param null $data
     * @param null $whiteList
     * @return bool
     * @throws ModelNotSaveException
     */
    public function saveOrFail($data = null, $whiteList = null)
    {
        $result = $this->save($data, $whiteList);
        if (!$result) {
            $outputMessages = [];
            $messages = $this->getMessages();
            foreach ($messages as $message) {
                $outputMessages[] = $message->getMessage();
            }
            throw new ModelNotSaveException('Model Save Exception:' . implode("\n", $outputMessages));
        }
        return $result;
    }

    /**
     * @param null $data
     * @param null $whiteList
     * @return bool
     * @throws ModelNotSaveException
     */
    public function createOrFail($data = null, $whiteList = null)
    {
        $result = $this->create($data, $whiteList);
        if (!$result) {
            $outputMessages = [];
            $messages = $this->getMessages();
            foreach ($messages as $message) {
                $outputMessages[] = $message->getMessage();
            }
            throw new ModelNotSaveException('Model Save Exception:' . implode("\n", $outputMessages));
        }
        return $result;
    }

    /**
     * @param null $data
     * @param null $whiteList
     * @return bool
     * @throws ModelNotSaveException
     */
    public function updateOrFail($data = null, $whiteList = null)
    {
        $result = $this->update($data, $whiteList);
        if (!$result) {
            $outputMessages = [];
            $messages = $this->getMessages();
            foreach ($messages as $message) {
                $outputMessages[] = $message->getMessage();
            }
            throw new ModelNotSaveException('Model Save Exception:' . implode("\n", $outputMessages));
        }
        return $result;
    }


    /**
     * @param mixed $data
     * @param mixed $columns
     * @param bool $forUpdate
     * @return \Phalcon\Mvc\Model\Criteria
     */
    protected static function buildSimpleQuery($data = null, $columns = null, $forUpdate = false)
    {
        $query = self::query();
        if (empty($data)) {
            //do nothing
        } else if (!is_array($data)) {
            $id = $data;
            $query->inWhere('id', [$id]);
        } elseif (is_array($data)) {
            $index = 0;
            $bindParams = [];
            foreach ($data as $i => $item) {
                $index++;
                $bindKey = 'bind' . $index;
                $key = $i;
                $op = '=';
                $value = null;
                if (is_string($i)) {
                    $value = $item;
                } else {
                    $key = $item[0];
                    if (count($item) == 2) {
                        $value = $item[1];
                    } elseif (count($item) == 3) {
                        $op = $item[1];
                        $value = $item[2];
                    } else {
                        continue;
                    }
                }
                if ($op == '=' && is_array($value)) {
                    $op = 'in';
                }
                if (in_array($op, ['in', 'not in'])) {
                    $value = is_array($value) ? $value : [$value];
                    if ($op == 'in' && empty($value)) {
                        $query->andWhere('1 = 0');
                        continue;
                    }
                    if ($op == 'not in' && empty($value)) {
                        continue;
                    }
                }
                $v = is_array($value) ? "({{$bindKey}:array})" : "{{$bindKey}}";
                $query->andWhere("{$key} {$op} {$v}");
                $bindParams[$bindKey] = $value;
            }
            $query->bind($bindParams);
        }
        if ($columns !== null) {
            $query->columns($columns);
        }
        if ($forUpdate) {
            $query->forUpdate();
        }
        return $query;
    }
}
