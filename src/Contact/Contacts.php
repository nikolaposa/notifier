<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Contact;

use Countable;
use IteratorAggregate;
use ArrayIterator;
use Notify\Exception\InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Contacts implements Countable, IteratorAggregate
{
    /**
     * @var ContactInterface[]
     */
    private $contacts = [];

    /**
     * @var array
     */
    private $contactsByType = [];

    /**
     * @param array $contacts
     * @throws InvalidContactsItemException
     */
    public function __construct(array $contacts = [])
    {
        foreach ($contacts as $contact) {
            if (!$contact instanceof ContactInterface) {
                throw new InvalidArgumentException(sprintf(
                    '%s expects array of %s instances, %s given',
                    __METHOD__,
                    ContactInterface::class,
                    is_object($contact) ? get_class($contact) : gettype($contact)
                ));
            }

            $this->add($contact);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->contacts);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->contacts);
    }

    /**
     * @return ContactInterface|false
     */
    public function first()
    {
        return reset($this->contacts);
    }

    /**
     * @return ContactInterface|false
     */
    public function last()
    {
        return end($this->contacts);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->contacts);
    }

    /**
     * @param ContactInterface $contact
     * @return void
     */
    public function add(ContactInterface $contact)
    {
        $this->contacts[] = $contact;
        $this->contactsByType[get_class($contact)][] = $contact;
    }

    /**
     * @param string $type Contact type (class name)
     * @return bool
     */
    public function has($type)
    {
        return !empty($this->contactsByType[$type]);
    }

    /**
     * @param string $type Contact type (class name)
     * @return self
     */
    public function getAll($type)
    {
        return new self(isset($this->contactsByType[$type])
            ? $this->contactsByType[$type]
            : []
        );
    }

    /**
     * @param string $type Contact type (class name)
     * @return ContactInterface|false
     */
    public function getOne($type)
    {
        return $this->getAll($type)->first();
    }

    /**
     * @param string $type
     * @param string $name
     * @return ContactInterface|false
     */
    public function find($type, $name)
    {
        foreach ($this->getAll($type) as $contact) {
            /* @var $contact ContactInterface */
            if ($contact->getName() == $name) {
                return $contact;
            }
        }

        return false;
    }
}
