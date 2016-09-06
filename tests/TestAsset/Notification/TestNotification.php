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
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TestNotification extends AbstractNotification
{
    protected function createEmailMessage(Recipients $messageRecipients)
    {
        return new EmailMessage(
            $messageRecipients,
            'Test notification',
            'test notification'
        );
    }
}
