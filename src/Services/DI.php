<?php

namespace PhalconExt\Services;

use Phalcon\Config;
use Phalcon\Mvc\Url;

/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/10
 * Time: 11:34 AM
 */
class DI
{
    /**
     * @return Config
     */
    public static function getConfig()
    {
        $di = \Phalcon\Di::getDefault();
        return $di->get('config');
    }

    /**
     * @return \Phalcon\Db\Adapter
     */
    public static function getDb()
    {
        $di = \Phalcon\Di::getDefault();
        return $di->get('db');
    }

    /**
     * @return \Phalcon\Http\Request
     */
    public static function getRequest()
    {
        $di = \Phalcon\Di::getDefault();
        return $di->get('request');
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public static function getResponse()
    {
        $di = \Phalcon\Di::getDefault();
        return $di->get('response');
    }

    /**
     * @return Url
     */
    public static function getUrl()
    {
        $di = \Phalcon\Di::getDefault();
        return $di->get('url');
    }


}