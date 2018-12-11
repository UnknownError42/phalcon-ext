<?php

namespace PhalconExt\Validator;

use Phalcon\Validation\Message;


class Json extends \Phalcon\Validation\Validator
{

    /**
     * Executes the validation
     *
     * @param \Phalcon\Validation $validation
     * @param string $attribute
     * @return bool
     */
    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        $value = $validation->getValue($attribute);
        $result = json_decode($value);
        if (!$result) {
            $validation->appendMessage(new Message($attribute . '不符合JSON格式', $attribute, 'not match', -1));
        }
        return $result;
    }
}