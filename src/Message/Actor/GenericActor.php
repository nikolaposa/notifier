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
class GenericActor implements ActorInterface, HasNameInterface
{
    /**
     * @var ContactInterface
     */
    private $contact;

    /**
     * @var string|null
     */
    private $name;

    public function __construct(ContactInterface $contact, $name = null)
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
}
