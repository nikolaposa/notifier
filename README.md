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

The essence of using this librarly is creation of notifications, whose task is to construct messages
(for example email, SMS, push) and send them using a strategy.

Notifications are defined through the `NotificationInterface`. Typically, concrete notifications
would inherit `BaseNotification` class, which provides some common basis.

### Messages

`MessageInterface` implementations are generic objects containing information about a message that
should be sent by the appropriate handler. At least, each message must provide recipients list and
content that is to be sent. Recipients list is a collection of `RecipientInterface` instances,
each represented by name and a contact information, encapsulated in the `ContactInterface`
implementation. Content is represented by the `ContentInterface`, whereas concrete implementations
are `TextContent` and `CallbackContent`.

Messages are sent using message `HandlerInterface` implementations.

Out of the box, only `EmailMessage` and related `NativeMailerHandler` is supported, but custom can
be created and consumed.

### Strategies

Strategies are `StrategyInterface` implementations, responsible for handling messages defined by
some notification. `SendStrategy` is a concrete, default Strategy implementation that sends messages
using appropriate message handlers.

This concept allows defining custom handling strategies, for example some that will put messages
into a background job.

## Example

```php
<?php

use Notify\BaseNotification;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Contact\EmailContact;
use Notify\Message\Content\TextContent;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;
use Notify\Strategy\SendStrategy;
use Notify\Message\Handler\NativeMailerHandler;

final class SampleNotification extends BaseNotification
{
    public function getName()
    {
        return 'Sample notification';
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

$defaultStrategy = new SendStrategy([
    EmailMessage::class => new NativeMailerHandler(),
]);

//set default strategy so that it do not have to be passed
//with each and every notification __invoke() call.
BaseNotification::setDefaultStrategy($defaultStrategy);

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