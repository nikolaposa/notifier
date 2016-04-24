# Notify

[![Build Status](https://travis-ci.org/nikolaposa/notify.svg?branch=master)](https://travis-ci.org/nikolaposa/notify)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nikolaposa/notify/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/notify/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nikolaposa/notify/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/notify/?branch=master)

PHP library which provides abstractions for modeling and implementing notifications functionality in
some application.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following
command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/notify
```

## Theory of operation

The essence of using this librarly is creation of notifications, whose task is to construct and
provide messages (for example email, SMS, push) and send them using a strategy.

Notifications are defined through the `NotificationInterface`. Typically, concrete notifications
would inherit `AbstractNotification` class, which provides some common basis.

### Messages

`MessageInterface` implementations are generic objects containing information about a message that
should be sent by the appropriate send service. At least, each message must provide recipients list
and content that is to be sent. Recipients list is a collection of `ActorInterface` instances,
each represented by name and a contact information, encapsulated in the `ContactInterface`
implementation. Content is essentially a string, but it can be supplied to a message in form of a
`ContentProviderInterface` implementation.

Messages are sent using message `SendServiceInterface` implementations.

Out of the box, Notify provides email, SMS and push message types, as well as their related send
services.

### Strategies

Strategies are `StrategyInterface` implementations, responsible for handling a notification, namely
its messages. `SendStrategy` is a concrete, default Strategy implementation that sends notification
messages using appropriate send services.

This concept allows defining custom handling strategies, for example some that will put notification
messages into a background job.

## Example

```php
<?php

use Notify\AbstractNotification;
use Notify\Message\EmailMessage;
use Notify\Message\SMSMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\EmailContact;
use Notify\Contact\PhoneContact;
use Notify\Message\Options\Options;
use Notify\Strategy\SendStrategy;
use Notify\Message\SendService\NativeMailer;
use Notify\Message\SendService\TwilioSMS;

final class SampleNotification extends AbstractNotification
{
    public function getName()
    {
        return 'Sample';
    }

    public function getMessages()
    {
        return [
            new EmailMessage(
                new Recipients([
                    new Actor(new EmailContact('john@example.com'), 'John Doe'),
                ]),
                'Notification exercise',
                'Some <strong>HTML</strong> notification content',
                null,
                new Options(['content_type' => 'text/html'])
            ),
            new SMSMessage(
                new Recipients([
                    new Actor(new PhoneContact('+12222222222'))
                ]),
                'test test test',
                new Actor(new PhoneContact('+11111111111'))
            )
        ];
    }
}

$defaultStrategy = new SendStrategy([
    EmailMessage::class => new NativeMailer(),
    SMSMessage::class => new TwilioSMS('token', 'id'),
]);

//set default strategy so that it do not have to be passed
//with each and every notification __invoke() call.
AbstractNotification::setDefaultStrategy($defaultStrategy);

$notification = new SampleNotification();
$notification();

```

See [more examples](https://github.com/nikolaposa/notify/tree/master/examples).

## Author

**Nikola Poša**

* https://twitter.com/nikolaposa
* https://github.com/nikolaposa

## Copyright and license

Copyright 2016 Nikola Poša. Released under MIT License - see the `LICENSE` file for details.