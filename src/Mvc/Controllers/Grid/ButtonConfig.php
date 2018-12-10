<?php

namespace PhalconExt\Mvc\Controllers\Grid;

class ButtonConfig
{
    protected $key;
    protected $name;
    protected $props = [
        'type' => 'primary',
        'size' => 'small',
    ];
    protected $style = [
        'marginRight' => '5px'
    ];
    protected $click;

    public function __construct(string $name, string $click, string $key)
    {
        $this->key = $key;
        $this->name = $name;
        $this->click = $click;
    }

    public static function create(string $name, string $click, string $key = null)
    {
        return new static($name, $click, $key === null ? $click : $key);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ButtonConfig
     */
    public function setKey(string $key): ButtonConfig
    {
        $this->key = $key;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return ButtonConfig
     */
    public function setName($name): ButtonConfig
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * @param array $props
     * @return ButtonConfig
     */
    public function setProps(array $props): ButtonConfig
    {
        $this->props = $props;
        return $this;
    }

    /**
     * @return array
     */
    public function getStyle(): array
    {
        return $this->style;
    }

    /**
     * @param array $style
     * @return ButtonConfig
     */
    public function setStyle(array $style): ButtonConfig
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClick()
    {
        return $this->click;
    }

    /**
     * @param mixed $click
     * @return ButtonConfig
     */
    public function setClick($click): ButtonConfig
    {
        $this->click = $click;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'props' => $this->props,
            'style' => $this->style,
            'click' => $this->click,
        ];
    }
}