<?php

namespace PhalconExt\Services;


class DB
{
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