<?php

declare(strict_types=1);

namespace Notify\Message;

class Options
{
    /**
     * @var array
     */
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function has($name) : bool
    {
        return array_key_exists($name, $this->options);
    }

    public function get($name, $default = null)
    {
        if (! $this->has($name)) {
            return $default;
        }

        return $this->options[$name];
    }
    
    public function toArray() : array
    {
        return $this->options;
    }
}
