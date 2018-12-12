<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/12
 * Time: 9:41 PM
 */

namespace PhalconExt\Services;


use Phalcon\Text;
use PhalconExt\Utils\WebUtil;

class Web
{
    /**
     * 获取根域名
     * @return string
     */
    public static function getSecondLevelDomain()
    {
        $request = DI::getRequest();
        $host = $request->getHttpHost();
        return WebUtil::getSecondLevelDomain($host);
    }

    /**
     * 获取url
     * @param $path
     * @param array $params
     * @param null $schema
     * @return string
     */
    public static function getAbsoluteUrl($path, $params = [], $schema = null)
    {
        $request = DI::getRequest();
        $schema = empty($schema) ? $request->getScheme() : $schema;
        return WebUtil::url($schema, $request->getHttpHost(), $path, $params);
    }
}