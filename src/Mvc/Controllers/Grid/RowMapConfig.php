<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/9/7
 * Time: 下午6:47
 */

namespace PhalconExt\Mvc\Controllers\Grid;

class RowMapConfig
{
    protected $key;
    protected $column;
    protected $listData;

    /**
     * GridRowMapConfig constructor.
     * @param string $key
     * @param string $column
     * @param array $listData
     */
    public function __construct(string $key, string $column, array $listData)
    {
        $this->key = $key;
        $this->column = $column;
        $this->listData = $listData;
    }

    /**
     * @param string $key
     * @param string $column
     * @param array $listData
     * @return RowMapConfig
     */
    public static function create(string $key, string $column, array $listData)
    {
        return new static($key, $column, $listData);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return RowMapConfig
     */
    public function setKey(string $key): RowMapConfig
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @param string $column
     * @return RowMapConfig
     */
    public function setColumn(string $column): RowMapConfig
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @return array
     */
    public function getListData(): array
    {
        return $this->listData;
    }

    /**
     * @param array $listData
     * @return RowMapConfig
     */
    public function setListData(array $listData): RowMapConfig
    {
        $this->listData = $listData;
        return $this;
    }
}