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
class MobileDeviceContact extends AbstractContact
{
    /**
     * @var array
     */
    protected $parameters;

    public function __construct($value, $name = null, array $parameters = [])
    {
        parent::__construct($value, $name);

        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}
