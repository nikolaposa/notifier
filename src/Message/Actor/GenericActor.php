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

use Notify\Contact\ContactInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class GenericActor implements ActorInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ContactInterface
     */
    private $contact;

    public function __construct($name, ContactInterface $contact)
    {
        $this->name = $name;
        $this->contact = $contact;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getContact()
    {
        return $this->contact;
    }
}
