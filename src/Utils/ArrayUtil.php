<?php

namespace PhalconExt\Utils;

class ArrayUtil
{
    /**
     * 对数组进行分组
     * @param array $array
     * @param string $column
     * @param bool $assoc
     * @return array
     */
    public static function group(array $array, string $column, bool $assoc = false)
    {

        $a = [];
        foreach ($array as $k => $value) {
            $field = $value[$column];
            $a[$field] = empty($a[$field]) ? [] : $a[$field];
            $assoc ? $a[$field][$k] = $value : $a[$field][] = $value;
        }
        return $a;
    }

    /**
     * 对数组的key进行驼峰处理
     * @param array $array
     * @return array
     */
    public static function camelCaseKeys(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[camel_case($key)] = is_array($value) ? self::camelCaseKeys($value) : $value;
        }
        return $result;
    }

    /**
     * 对数组的value进行驼峰处理
     * @param array $array
     * @return array
     */
    public static function cameCaseValues(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = is_array($value) ? self::cameCaseValues($value) : camel_case($value);
        }
        return $result;
    }

    /**
     * 对数组的key进行下划线处理
     * @param array $array
     * @return array
     */
    public static function snakeCaseKeys(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[snake_case($key)] = is_array($value) ? self::snakeCaseKeys($value) : $value;
        }
        return $result;
    }

    /**
     * 对数组进行值下划线处理
     * @param array $array
     * @return array
     */
    public static function snakeCaseValues(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = is_array($value) ? self::snakeCaseValues($value) : snake_case($value);
        }
        return $result;
    }

    /**
     * 获取数组元素
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public static function get(array $array, string $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}