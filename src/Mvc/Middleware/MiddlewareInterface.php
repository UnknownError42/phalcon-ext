<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/9/8
 * Time: 下午8:59
 */

namespace PhalconExt\Mvc\Middleware;

interface MiddlewareInterface
{
    /**
     * @param $dispatcher
     * @return bool
     */
    public function execute($dispatcher): bool;
}