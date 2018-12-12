<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/12
 * Time: 9:38 PM
 */

namespace PhalconExt\Services;


use Phalcon\Validation;
use PhalconExt\Exceptions\Exception;

class Validator
{
    /**
     * @param array $data
     * @param Validation $validator
     * @throws Exception
     */
    public static function passOrFail(array $data, Validation $validator)
    {
        $messages = $validator->validate($data);
        if ($messages->count()) {
            $current = $messages->current();
            throw new Exception($current->getMessage());
        }
    }
}