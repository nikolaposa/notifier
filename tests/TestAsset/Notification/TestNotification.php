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

use Notify\BaseNotification;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Contact\EmailContact;
use Notify\Message\Content\TextContent;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TestNotification extends BaseNotification
{
    public function getName()
    {
        return 'Test notification';
    }

    protected function getMessages()
    {
        return [
            new EmailMessage(
                new Recipients([
                    new Recipient('John Doe', new EmailContact('john@example.com')),
                ]),
                'Notification exercise',
                new TextContent('Some <strong>HTML</strong> notification content'),
                new EmptySender(),
                new EmailOptions('text/html')
            ),
        ];
    }
}
