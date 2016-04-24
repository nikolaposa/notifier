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
 * Generic message options.
 *
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Options
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

    public function getAll()
    {
        return $this->options;
    }
}
