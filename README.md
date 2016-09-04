# Notify

[![Build Status](https://travis-ci.org/nikolaposa/notify.svg?branch=master)](https://travis-ci.org/nikolaposa/notify)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nikolaposa/notify/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/notify/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nikolaposa/notify/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/notify/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nikolaposa/notify/v/stable)](https://packagist.org/packages/nikolaposa/notify)

PHP library which provides abstractions for creating notifications system in some application.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following
command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/notify
```

## Theory of operation

Notifications are informational messages that notify users about something that happened in your
application. For example, in case of some employee scheduling application, you might need to send
a "Shift has been created" notification.

Notifications can be sent via different delivery channels, for example email, SMS, mobile push
notifications, and similar.

### Creating notifications

Notifications are represented by the `NotificationInterface`. Each notification should provide its
name, list of supported channels, as well as message object for some channel when it is being sent.

`AbstractNotification` facilitates notifications creation in terms of implementing
`getSupportedChannels()` and `getMessage()` methods. In turn, you need to define message factory
methods named in accordance with a pre-defined template, for example `createEmailMessage`, whereas
`Email` is the name of a channel, which is dynamically reflected as a channel that it is supported
by the particular notification.

### Channels / Messages

Out of the box, Notify provides sending notifications in form of email, SMS and mobile push messages.

`MessageInterface` implementations are objects that represent actual message to be sent over some
channel and they are modeled in accordance with the type of a channel. Examples are `EmailMessage`,
`SMSMessage`, etc. Typically, every message provides list of recipients and content that is to be
sent. Messages are sent using `MessageSenderInterface` implementations.

### Notify Strategies

Component that how Notification should be handled are strategies. `DefaultStrategy` immediately sends
notification messages using appropriate message senders.

This concept allows defining custom handling strategies, for example some that will put notification
into a queue, in order to send it in a background job.

### Notification recipients

In order to send a Notification using a strategy, recipients must also be provided. Recipients are
defined using the `NotificationRecipientInterface` and it will typically be implemented by a class
that represents user in your application.

## Examples

**Sample notification**

```php
<?php

namespace App\Notification;

use Notify\AbstractNotification;
use Notify\Message\EmailMessage;
use App\Entity\Post;
use App\Entity\Comment;

final class NewCommentNotification extends AbstractNotification
{
    private $post;

    private $comment;

    public function __construct(Post $post, Comment $comment)
    {
        $this->post = $post;
        $this->comment = $comment;
    }

    public function createEmailMessage($channel, NotificationRecipientInterface $recipient)
    {
        return new EmailMessage(
            $this->createRecipients($recipient, $channel),
            'New comment',
            sprintf('%s left a new comment on your "%s" blog post', $this->comment->getAuthorName(), $this->post->getTitle())
        );
    }
}
```

**Sending notifications**

```php
use Notify\Strategy\DefaultStrategy;
use Notify\Strategy\ChannelHandler;
use Notify\Message\Sender\NativeMailer;

$newCommentNotification = new NewCommentNotification($post, $comment);

$moderators = $this->getUserRepository()->getModerators();

$notifyStrategy = new DefaultStrategy([
    new ChannelHandler('Email', new NativeMailer()),
]);
$notifyStrategy->notify($moderators, $newCommentNotification);
```

## Author

**Nikola Poša**

* https://twitter.com/nikolaposa
* https://github.com/nikolaposa

## Copyright and license

Copyright 2016 Nikola Poša. Released under MIT License - see the `LICENSE` file for details.