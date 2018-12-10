<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/10
 * Time: 4:20 PM
 */

namespace PhalconExt\Tests;

use PhalconExt\Utils\ArrayUtil;
use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{
    public function testGroup()
    {
        $array = [
            [
                'id' => '1',
                'group' => '1',
            ],

            [
                'id' => '2',
                'group' => '2',
            ],
            [
                'id' => '3',
                'group' => '1',
            ],
            [
                'id' => '4',
                'group' => '2',
            ],
        ];
        $result = ArrayUtil::group($array, 'group');
        $this->assertEquals([
            '1' => [
                [
                    'id' => '1',
                    'group' => '1',
                ],
                [
                    'id' => '3',
                    'group' => '1',
                ],
            ],
            '2' => [
                [
                    'id' => '2',
                    'group' => '2',
                ],
                [
                    'id' => '4',
                    'group' => '2',
                ],
            ]
        ], $result);
    }
}