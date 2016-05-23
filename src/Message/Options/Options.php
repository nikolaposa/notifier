<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Options;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Options implements OptionsInterface
{
    /**
     * @var array
     */
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function has($name)
    {
        return array_key_exists($name, $this->options);
    }

    public function get($name, $default = null)
    {
        if (!$this->has($name)) {
            return $default;
        }

        return $this->options[$name];
    }

    public function set($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function toArray()
    {
        return $this->options;
    }
}
