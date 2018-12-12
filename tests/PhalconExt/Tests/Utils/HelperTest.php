<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/10
 * Time: 3:38 PM
 */

namespace PhalconExt\Tests\Utils;


class HelperTest extends \PHPUnit\Framework\TestCase
{
    protected $array = [
        'key' => 'value',
        'array_key' => [
            'dotKey' => 'dotValue',
        ],
    ];

    public function testArrayGetOne()
    {
        $array = $this->array;
        $result = array_get($array, 'key');
        $this->assertEquals('value', $result);
    }

    public function testArrayGetNone()
    {
        $result = array_get($this->array, 'none');
        $this->assertEquals(null, $result);
    }

    public function testArrayGetDefault()
    {
        $default = 'default';
        $result = array_get($this->array, 'none', $default);
        $this->assertEquals($default, $result);
    }

    public function testArrayGetDot()
    {
        $result = array_get($this->array, 'array_key.dotKey');
        $this->assertEquals('dotValue', $result);
    }
}