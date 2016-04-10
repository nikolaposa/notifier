<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Notify\BaseNotification;
use Notify\Message\EmailMessage;
use Notify\Message\SmsMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Contact\EmailContact;
use Notify\Contact\PhoneContact;
use Notify\Message\Content\TextContent;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;
use Notify\Strategy\SendStrategy;
use Notify\Message\Handler\TestHandler;

final class TestNotification extends BaseNotification
{
    public function getName()
    {
        return 'New comment notification';
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
            new SmsMessage(
                new Recipients([
                    new Recipient('John Doe', new PhoneContact('+111111111')),
                ]),
                new TextContent('Notification exercise')
            ),
        ];
    }
}

$handler = new TestHandler();

$newCommentNotification = new TestNotification($post, $comment);
$newCommentNotification(new SendStrategy([
    EmailMessage::class => $handler,
    SmsMessage::class => $handler,
]));

foreach ($handler->getMessages() as $message) {
    echo get_class($message) . ': ';
    echo htmlentities($message->getContent()->get());
    echo "\n\n";
}

