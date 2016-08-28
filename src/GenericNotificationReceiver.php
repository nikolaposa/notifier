<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

use Notify\Contact\Contacts;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class GenericNotificationReceiver implements NotificationReceiverInterface
{
    /**
     * @var Contacts
     */
    private $contacts;

    public function __construct(Contacts $contacts)
    {
        $this->contacts = $contacts;
    }

    public function shouldReceiveNotification($channelName, NotificationInterface $notification)
    {
        return $this->contacts->has($channelName);
    }

    public function getNotifyContact($channelName, NotificationInterface $notification)
    {
        return $this->contacts->getOne($channelName);
    }
}
