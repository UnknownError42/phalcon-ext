<?php

namespace PhalconExt\Cache\Frontend;

use Phalcon\Cache\FrontendInterface;

class Json implements FrontendInterface
{
    protected $options;

    public function __construct(array $options = null)
    {
        $this->options = $options;
    }

    /**
     * Returns the cache lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        $options = $this->options;
        return isset($options['lifetime']) ? $options['lifetime'] : 1;
    }

    /**
     * Check whether if frontend is buffering output
     *
     * @return bool
     */
    public function isBuffering()
    {
        return false;
    }

    /**
     * Starts the frontend
     */
    public function start()
    {

    }

    /**
     * Returns output cached content
     *
     * @return string
     */
    public function getContent()
    {
        return null;
    }

    /**
     * Stops the frontend
     */
    public function stop()
    {

    }

    /**
     * Serializes data before storing it
     *
     * @param mixed $data
     * @return false|string
     */
    public function beforeStore($data)
    {
        return json_encode($data, isset($this->options['jsonOptions']) ? $this->options['jsonOptions'] : JSON_UNESCAPED_UNICODE);
    }

    /**
     * Unserializes data after retrieving it
     *
     * @param mixed $data
     * @return mixed
     */
    public function afterRetrieve($data)
    {
        return json_decode($data, isset($this->options['assoc']) ? $this->options['assoc'] : true);
    }
}