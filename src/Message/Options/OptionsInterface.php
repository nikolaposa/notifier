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
interface OptionsInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function has($name);

    /**
     * @param string $name
     * @param mixed $default
     */
    public function get($name, $default = null);

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set($name, $value);

    /**
     * @return array
     */
    public function toArray();
}
