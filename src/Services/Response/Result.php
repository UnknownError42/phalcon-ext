<?php

namespace PhalconExt\Services\Response;

use PhalconExt\Utils\ArrayUtil;

class Result
{
    /**
     *  返回的消息信息
     * @var string $message
     */
    private $message;

    /**
     * 返回的业务码
     * @var number $code
     */
    private $code;

    /**
     * 返回的业务数据
     * @var array
     */
    private $data;

    /**
     * @return string
     */
    public function getMesage(): string
    {
        return $this->message;
    }

    /**
     * @return number
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * ResultData constructor.
     * @param int $code
     * @param string $message
     * @param array $data
     */
    public function __construct(int $code, string $message, array $data = [])
    {
        $this->code = $code;
        $this->message = trim($message);
        $this->data = $data;
    }

    /**
     * 通过该方法，完成了数据协议的封装
     * @return array
     */
    public function getResult()
    {
        $result = [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ];
        // api消耗的时间
        $result['use_time'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
        return ArrayUtil::camelCaseKeys($result);
    }

    /**
     * 得到处理后的json数据
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->getResult(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 根据 api code 判断是否成功
     */
    public function isSuccess()
    {
        return $this->code == 0;
    }
}