<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/10
 * Time: 4:57 PM
 */

namespace PhalconExt\Tests\Mvc\Controllers\Grid;


use PhalconExt\Mvc\Controllers\Grid\ButtonConfig;
use PHPUnit\Framework\TestCase;

class ButtonConfigTest extends TestCase
{
    public function testSample()
    {
        $bc = ButtonConfig::create('test', 'save');
        $this->assertEquals([
            'key' => 'save',
            'name' => 'test',
            'props' => $bc->getProps(),
            'style' => $bc->getStyle(),
            'click' => 'save',
        ], $bc->toArray());
    }
}