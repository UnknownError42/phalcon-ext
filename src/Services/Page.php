<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/10
 * Time: 4:49 PM
 */

namespace PhalconExt\Services;

use Phalcon\Mvc\Model\Criteria;

class Page
{
    /**
     * @param Criteria $query
     * @param int $perPage
     * @param int $page
     * @param null $concrete
     * @return array
     */
    public static function result(Criteria $query, int $perPage = null, int $page = 1, $concrete = null)
    {
        $perPage = $perPage === null ? 10 : $perPage;
        $limit = $perPage;
        $offset = ($page - 1) * $perPage;
        $modelClass = $query->getModelName();
        $total = $modelClass::count($query->getParams());
        $lastPage = ceil($total / $perPage);
        $data = $query->limit($limit, $offset)->execute()->toArray();
        if ($concrete instanceof \Closure) {
            $data = $concrete($data);
        } elseif (is_array($concrete)) {
            $data = $concrete;
        }
        return [
            'list' => $data,
            'total' => $total,
            'currentPage' => $page,
            'lastPage' => $lastPage,
            'perPage' => $perPage,
        ];
    }
}