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
class Actor implements ActorInterface, HasNameInterface
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

    /**
     * {@inheritDoc}
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        if (null !== ($name = $this->getName())) {
            return $name . ' <' . $this->getContact()->getValue() . '>';
        }

        return $this->getContact()->getValue();
    }
}
