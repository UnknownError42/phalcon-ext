<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/12
 * Time: 9:48 PM
 */

namespace PhalconExt\Utils;


use Phalcon\Text;

class WebUtil
{
    /**
     * @param string $host
     * @return string
     */
    public static function getSecondLevelDomain(string $host): string
    {
        $host = explode(':', $host, 2)[0];
        $domainArray = explode('.', $host);
        if (is_numeric($domainArray[count($domainArray) - 1])) {
            return $host;
        }
        $domain = implode('.', array_slice($domainArray, -2, 2));
        return $domain;
    }

    /**
     * @param string $schema
     * @param string $host
     * @param string $path
     * @param array $params
     * @return string
     */
    public static function url(string $schema, string $host, string $path, array $params = []): string
    {
        $paramsString = http_build_query($params);
        $schema = str_replace('://', '', $schema);
        $url = $schema . '://' . Text::concat('/', $host, $path);
        $sep = '?';
        if (strstr($url, '?') !== false) {
            $sep = '&';
        }
        $url = $paramsString ?  $url . $sep . $paramsString : $url;
        return $url;
    }
}