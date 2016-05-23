<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Contact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractContact implements ContactInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string|null
     */
    private $name;

    public function __construct($value, $name = null)
    {
        $this->value = $this->filter($value);
        $this->name = $name;
    }

    protected function filter($value)
    {
        return $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function hasName()
    {
        return (null !== $this->name);
    }
}
