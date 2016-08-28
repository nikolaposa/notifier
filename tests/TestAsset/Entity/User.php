<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\TestAsset\Entity;

use Notify\NotificationReceiverInterface;
use Notify\Contact\Contacts;
use Notify\NotificationInterface;
use Notify\Contact\GenericContact;
use Notify\Contact\EmailContact;
use Notify\Contact\PhoneContact;
use Notify\Contact\MobileDeviceContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class User implements NotificationReceiverInterface
{
    /**
     * @var Contacts
     */
    private $contacts;

    /**
     * @var array
     */
    private static $channelContactTypeMap = [
        'test' => GenericContact::class,
        'email' => EmailContact::class,
        'sms' => PhoneContact::class,
        'push' => MobileDeviceContact::class,
    ];

    public function __construct(Contacts $contacts)
    {
        $this->contacts = $contacts;
    }

    public function shouldReceiveNotification($channelName, NotificationInterface $notification)
    {
        if (!isset(self::$channelContactTypeMap[$channelName])) {
            return false;
        }

        return $this->contacts->has(self::$channelContactTypeMap[$channelName]);
    }

    public function getNotifyContact($channelName, NotificationInterface $notification)
    {
        if (!isset(self::$channelContactTypeMap[$channelName])) {
            //@todo throw
        }

        $contactType = self::$channelContactTypeMap[$channelName];

        $contact = $this->contacts->getOne($contactType);

        if (false === $contact) {
            //@todo throw
        }

        return $contact;
    }
}
