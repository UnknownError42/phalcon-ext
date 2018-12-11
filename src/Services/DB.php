<?php

namespace PhalconExt\Services;


use Phalcon\Db\Adapter\Pdo;

class DB
{
    /**
     * @param string $db
     * @return Pdo
     */
    public static function get(string $db = 'db')
    {
        $di = \Phalcon\Di::getDefault();
        return $di->get('db');
    }

    /**
     * @param \Phalcon\Db\AdapterInterface $db
     * @param callable $fun
     * @return mixed
     * @throws \Exception
     */
    public static function transaction(\Phalcon\Db\AdapterInterface $db, callable $fun)
    {
        $db->begin();
        try {
            $funReturn = $fun();
            $db->commit();
            return $funReturn;
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}