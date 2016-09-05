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
use Notify\NotificationRecipientInterface;
use Notify\Message\EmailMessage;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TestNotification extends AbstractNotification
{
    public function createEmailMessage(NotificationRecipientInterface $recipient)
    {
        return new EmailMessage(
            $this->createRecipients($recipient, 'Email'),
            'Test notification',
            'test notification'
        );
    }
}
