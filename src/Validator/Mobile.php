<?php
namespace PhalconExt\Validator;

use Phalcon\Validation\Message;

class Mobile extends \Phalcon\Validation\Validator
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
        $result = boolval(preg_match("/^1\d{10}$/", $value));
        if (!$result) {
            $validation->appendMessage(new Message($attribute . '不符合手机号格式', $attribute, 'not match', -1));
        }
        return $result;
    }
}