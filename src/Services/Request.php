<?php

namespace PhalconExt\Services;


class Request
{
    private static $getData;
    private static $postData;

    public static function get(string $key = null, $default = null)
    {
        if (self::$getData === null) {
            $request = Di::getRequest();
            $data = $request->getJsonRawBody(true);
            $data = $data ?: [];
            $data += $request->getPost();
            $data += $request->getQuery();
            self::$getData = $data;
            self::$getData = self::trimData(self::$getData);
        }
        return $key === null ? self::$getData : array_get(self::$getData, $key, $default);
    }

    public static function post(string $key = null, $default = null)
    {
        if (self::$postData === null) {
            $request = Di::getRequest();
            self::$postData = $request->getJsonRawBody(true);
            if (empty(self::$postData)) {
                self::$postData = $request->getPost();
            }
            self::$postData = self::trimData(self::$postData);
        }
        return $key === null ? self::$postData : array_get(self::$postData, $key, $default);
    }

    protected static function trimData(array $params)
    {
        foreach ($params as $i => $param) {
            if (is_string($param)) {
                $params[$i] = trim($param);
            } elseif (is_array($param)) {
                $params[$i] = self::trimData($param);
            }
        }
        return $params;
    }
}