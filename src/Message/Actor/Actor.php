<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Actor;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Actor implements ActorInterface, HasNameInterface
{
    /**
     * @var string
     */
    private $contact;

    /**
     * @var string|null
     */
    private $name;

    public function __construct($contact, $name = null)
    {
        $this->contact = $contact;
        $this->name = $name;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        if (null !== ($name = $this->getName())) {
            return $name . ' <' . $this->getContact() . '>';
        }

        return $this->getContact();
    }
}
