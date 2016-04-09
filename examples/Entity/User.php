<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Example\Entity;

use Notify\Contact\HasContactsInterface;
use Notify\Contact\Contacts;
use Notify\Contact\EmailContact;
use Notify\Message\Actor\ProvidesRecipientInterface;
use Notify\Message\Actor\Recipient;
use Notify\Message\Actor\EmptyRecipient;
use Notify\Message\EmailMessage;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class User implements HasContactsInterface, ProvidesRecipientInterface
{
    /**
	 * @var string
	 */
    private $username;

    /**
	 * @var string
	 */
	private $firstName;

	/**
	 * @var string
	 */
	private $lastName;

    /**
     * @var string
     */
    private $email;

    public function __construct(
        $username,
        $firstName,
        $lastName,
        $email
    ) {
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getContacts()
    {
        return new Contacts([
            new EmailContact($this->email),
        ]);
    }

    public function getMessageRecipient($notificationId, $messageType)
    {
        if ($messageType == EmailMessage::class) {
            return new Recipient(
                sprintf('%s %s', $this->getFirstName(), $this->getLastName()),
                $this->getContacts()->getOne(EmailContact::class)
            );
        }

        return new EmptyRecipient();
    }
}
