<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\TestAsset\Notification;

use Notify\AbstractNotification;
use Notify\NotificationReceiverInterface;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TestNotification extends AbstractNotification
{
    public function getName()
    {
        return 'Test';
    }

    public function createEmailMessage($channel, NotificationReceiverInterface $receiver)
    {
        return new EmailMessage(
            new Recipients([
                new Actor($receiver->getNotifyContact($channel, $this)),
            ]),
            'Test notification',
            'test notification'
        );
    }
}
