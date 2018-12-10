<?php

namespace PhalconExt\Services;

use PhalconExt\Services\Response\Result;

/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/10
 * Time: 11:33 AM
 */
class Response
{
    protected static $headers = [];

    /**
     * @param $key
     * @param $value
     */
    public static function setHeader(string $key, string $value)
    {
        self::$headers[$key] = $value;
    }

    /**
     * @param array $data
     * @param string $message
     * @return \Phalcon\Http\Response
     */
    public static function success(array $data = [], string $message = 'ok')
    {
        $result = new Result(0, $message, $data);
        $response = Di::getResponse();
        foreach (self::$headers as $key => $value) {
            $response->setHeader($key, $value);
        }
        return $response
            ->setJsonContent($result->getResult(), JSON_UNESCAPED_UNICODE)
            ->send();
    }

    /**
     * @param string $code
     * @param string $message
     * @param array $data
     * @return \Phalcon\Http\Response
     */
    public static function error(string $code, string $message, array $data = [])
    {
        $result = new Result($code, $message, $data);
        $response = Di::getResponse();
        foreach (self::$headers as $key => $value) {
            $response->setHeader($key, $value);
        }
        return $response
            ->setJsonContent($result->getResult(), JSON_UNESCAPED_UNICODE)
            ->send();
    }

    /**
     * @param string $data
     * @param string $filename
     * @return \Phalcon\Http\Response
     */
    public static function download(string $data, string $filename)
    {
        self::setHeader('Content-type', 'application/octet-stream');
        self::setHeader('Accept-Ranges', 'bytes');
        self::setHeader('Accept-Length:', strlen($data));
        self::setHeader('Content-Disposition', 'attachment; filename=' . $filename);
        $response = Di::getResponse();
        foreach (self::$headers as $key => $value) {
            $response->setHeader($key, $value);
        }
        return $response->setContent($data)->send();
    }
}