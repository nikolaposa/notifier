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
use Notify\Message\SMSMessage;
use Notify\Contact\PhoneContact;
use Notify\Message\Actor\Actor;
use Notify\Message\Actor\Recipients;
use GuzzleHttp\Client;
use Notify\Message\Sender\TwilioSMS;
use Notify\Strategy\SendStrategy;

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
            new SMSMessage(
                new Recipients([
                    new Actor(new PhoneContact('+14108675309'), 'John Doe'),
                ]),
                'Notification exercise',
                new Actor(new PhoneContact('+15005550006'), 'Nikola Posa')
            ),
        ];
    }
}

$messageSender = new TwilioSMS(
    'auth_id',
    'auth_token',
    new Client(['verify' => false])
);

$notifyStrategy = new SendStrategy([
    SMSMessage::class => $messageSender,
]);

$notification = new TestNotification($post, $comment);

$notifyStrategy->handle($notification);
