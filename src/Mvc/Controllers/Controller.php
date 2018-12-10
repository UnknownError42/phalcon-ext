<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/8
 * Time: 9:49 PM
 */

namespace PhalconExt\Mvc\Controllers;


use Phalcon\Mvc\Dispatcher;
use PhalconExt\Mvc\Middleware\MiddlewareInterface;

class Controller extends \Phalcon\Mvc\Controller
{
    protected $middlewareClasses = [];
    protected $actionMiddlewareClasses = [];

    /**
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute($dispatcher)
    {
        $action = $dispatcher->getActionName();
        $middlewareClasses = isset($this->actionMiddlewareClasses[$action]) ? $this->actionMiddlewareClasses[$action] : $this->middlewareClasses;
        foreach ($middlewareClasses as $class) {
            /**
             * @var MiddlewareInterface $middleware
             */
            $middleware = new $class();
            $result = $middleware->execute($dispatcher);
            if (!$result) {
                return false;
            }
        }
        return true;
    }
}