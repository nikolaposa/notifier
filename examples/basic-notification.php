<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Example;

use Notify\AbstractNotification;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\EmailContact;
use Notify\Message\Options\EmailOptions;
use Notify\Strategy\SendStrategy;
use Notify\Message\SendService\MockSendService;

require_once __DIR__ . '/../vendor/autoload.php';

final class TestNotification extends AbstractNotification
{
    public function getName()
    {
        return 'Test notification';
    }

    public function getMessages()
    {
        return [
            new EmailMessage(
                new Recipients([
                    new Actor(new EmailContact('john@example.com', 'John Doe')),
                ]),
                'Notification exercise',
                'Some <strong>HTML</strong> notification content',
                null,
                new EmailOptions('text/html')
            ),
        ];
    }
}

$sendService = new MockSendService();

$notification = new TestNotification($post, $comment);
$notification(new SendStrategy([
    EmailMessage::class => $sendService,
]));

foreach ($sendService->getMessages() as $message) {
    echo get_class($message) . ': ';
    echo htmlentities($message->getContent());
    echo "\n\n";
}
