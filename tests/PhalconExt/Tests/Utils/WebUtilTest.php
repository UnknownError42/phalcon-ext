<?php
/**
 * Created by PhpStorm.
 * User: gengming
 * Date: 2018/12/12
 * Time: 9:59 PM
 */

namespace PhalconExt\Tests\Utils;


use PhalconExt\Utils\WebUtil;

class WebUtilTest extends \PHPUnit\Framework\TestCase
{
    public function testSecondLevelDomainCommon()
    {
        $host = 'www.yidu.com';
        $domain = WebUtil::getSecondLevelDomain($host);
        $this->assertEquals('yidu.com', $domain);
    }

    public function testSecondLevelDomainPort()
    {
        $host = 'www.yidu.com:8080';
        $domain = WebUtil::getSecondLevelDomain($host);
        $this->assertEquals('yidu.com', $domain);
    }

    public function testSecondLevelDomainIp()
    {
        $host = '192.168.1.1';
        $domain = WebUtil::getSecondLevelDomain($host);
        $this->assertEquals('192.168.1.1', $domain);
    }

    public function testSecondLevelDomainIpPort()
    {
        $host = '192.168.1.1:8080';
        $domain = WebUtil::getSecondLevelDomain($host);
        $this->assertEquals('192.168.1.1', $domain);
    }

    public function testUrlCommon()
    {
        $url = WebUtil::url('http', 'www.m.com', 'admin');
        $this->assertEquals('http://www.m.com/admin', $url);
    }

    public function testUrlSchema()
    {
        $url = WebUtil::url('https://', 'www.m.com', 'admin');
        $this->assertEquals('https://www.m.com/admin', $url);
    }

    public function testUrlPath1()
    {
        $url = WebUtil::url('https://', 'www.m.com', '/admin');
        $this->assertEquals('https://www.m.com/admin', $url);
    }

    public function testUrlPath2()
    {
        $url = WebUtil::url('https://', 'www.m.com/', 'admin');
        $this->assertEquals('https://www.m.com/admin', $url);
    }

    public function testUrlParams1()
    {
        $url = WebUtil::url('https://', 'www.m.com', '/admin', ['id' => 1]);
        $this->assertEquals('https://www.m.com/admin?id=1', $url);
    }

    public function testUrlParams2()
    {
        $url = WebUtil::url('https://', 'www.m.com', '/admin?a=3', ['id' => 1]);
        $this->assertEquals('https://www.m.com/admin?a=3&id=1', $url);
    }
}