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
use Notify\NotificationReceiverInterface;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\GenericNotificationReceiver;
use Notify\Contact\Contacts;
use Notify\Contact\EmailContact;
use Notify\Message\Options\Options;
use Notify\Strategy\SendStrategy;
use Notify\Message\Sender\TestMessageSender;

require_once __DIR__ . '/../vendor/autoload.php';

final class TestNotification extends AbstractNotification
{
    public function getName()
    {
        return 'Test notification';
    }

    public function createEmailMessage($channelName, NotificationReceiverInterface $receiver)
    {
        return new EmailMessage(
            new Recipients([
                new Actor($receiver->getNotifyContact($channelName, $this)),
            ]),
            'Notification exercise',
            'Some <strong>HTML</strong> notification content',
            null,
            new Options(['content_type' => 'text/html'])
        );
    }
}

$messageSender = new TestMessageSender();

$notifyStrategy = new SendStrategy([
    EmailMessage::class => $messageSender,
]);

$notification = new TestNotification($post, $comment);

$notifyStrategy->notify([
    new GenericNotificationReceiver(new Contacts([new EmailContact('test@example.com')]))
], $notification);

foreach ($messageSender->getMessages() as $message) {
    echo get_class($message) . ': ';
    echo htmlentities($message->getContent());
    echo "\n\n";
}
