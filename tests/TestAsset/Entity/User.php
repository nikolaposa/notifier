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

use Notify\NotificationRecipientInterface;
use Notify\Contact\Contacts;
use Notify\NotificationInterface;
use Notify\Contact\EmailContact;
use Notify\Contact\PhoneContact;
use Notify\Contact\MobileDeviceContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class User implements NotificationRecipientInterface
{
    /**
     * @var Contacts
     */
    private $contacts;

    /**
     * @var array
     */
    private $notified = [];

    /**
     * @var array
     */
    private static $channelContactTypeMap = [
        'Email' => EmailContact::class,
        'Sms' => PhoneContact::class,
        'Push' => MobileDeviceContact::class,
    ];

    public function __construct(Contacts $contacts)
    {
        $this->contacts = $contacts;
    }

    public function acceptsNotification(NotificationInterface $notification, $channel)
    {
        if (!isset(self::$channelContactTypeMap[$channel])) {
            return false;
        }

        return $this->contacts->has(self::$channelContactTypeMap[$channel]);
    }

    public function getNotifyContact($channel, NotificationInterface $notification)
    {
        if (!isset(self::$channelContactTypeMap[$channel])) {
            throw new \RuntimeException(sprintf(
                'User does not accept notifications through %s channel',
                $channel
            ));
        }

        $contactType = self::$channelContactTypeMap[$channel];

        $contact = $this->contacts->getOne($contactType);

        if (false === $contact) {
            throw new \RuntimeException(sprintf(
                'User does not accept notifications through %s channel',
                $channel
            ));
        }

        return $contact;
    }

    public function onNotified(NotificationInterface $notification, $channel)
    {
        $this->notified[$notification->getName()][$channel] = true;
    }
}
