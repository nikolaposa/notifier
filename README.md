# Notify

[![Build Status][ico-build]][link-build]
[![Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Latest Version][ico-version]][link-packagist]
[![PDS Skeleton][ico-pds]][link-pds]

PHP library which facilitates creation of a notifications system in some application.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/notifier
```

## Theory of operation

Notifications are informational messages that notify users about something that happened in your application. For example, in case of some employee scheduling application, you might need to send a "Shift has been created" notification.

Notifications can be sent via different delivery channels, for example email, SMS, mobile push notifications, and similar.

### Creating notifications

Notifications are represented by the `NotificationInterface`. Each notification should provide list of supported channels, as well as message object for some channel when it is to be sent.

`AbstractNotification` facilitates notifications creation in terms of implementing `getSupportedChannels()` and `getMessage()` methods. In turn, you need to define message factory methods named in accordance with a pre-defined template, for example `createEmailMessage`, whereas `email` is the name of a channel, which is dynamically reflected as a channel that it is supported by the particular notification.

### Channels / Messages

Out of the box, Notify enables for sending notifications via email, SMS and mobile push channels, by providing appropriate message objects and corresponding sender implementations. Examples are `EmailMessage`, `SMSMessage`, etc. Typically, every message provides list of recipients and content. Messages are sent using `MessageSenderInterface` implementations.

### Notifier

`NotiferInterface` is a central component that performs actual sending of the Notification object. Default implementation - `Notifier`, immediately sends notification messages using provided message senders.

This concept allows defining custom handling strategies, for example some that will put notification into a queue, in order to send it in a background job.

## Examples

**Sample notification**
```php
namespace App\Model;

use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Sms\SmsMessage;
use Notifier\Notification\EmailNotification;
use Notifier\Notification\SmsNotification;
use Notifier\Recipient\Recipient;

class TodoExpiredNotification implements EmailNotification, SmsNotification
{
    /** @var Todo */
    protected $todo;

    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }

    public function toEmailMessage(Recipient $recipient): EmailMessage
    {
        return (new EmailMessage())
            ->subject('Todo expired')
            ->body('Todo:' . $this->todo->getText() . ' has expired');
    }

    public function toSmsMessage(Recipient $recipient): SmsMessage
    {
        return (new SmsMessage())
            ->text('Todo:' . $this->todo->getText() . ' has expired');
    }
}
```

**Sending notifications**
```php
use Notifier\Channel\ChannelManager;
use Notifier\Channel\Email\SimpleEmailNotificationSender;
use Notifier\Notifier;
use Notifier\Recipient\Recipients;

$notifier = new Notifier(new ChannelManager([
    'email' => new SimpleEmailNotificationSender(),
]));

$notifier->send(new TodoExpiredNotification($todo), new Recipients($user));
```

## Credits

- [Nikola Poša][link-author]
- [All Contributors][link-contributors]

## License

Released under MIT License - see the [License File](LICENSE) for details.


[ico-version]: https://poser.pugx.org/nikolaposa/notify/v/stable
[ico-build]: https://travis-ci.org/nikolaposa/notify.svg?branch=master
[ico-code-coverage]: https://scrutinizer-ci.com/g/nikolaposa/notify/badges/coverage.png?b=master
[ico-code-quality]: https://scrutinizer-ci.com/g/nikolaposa/notify/badges/quality-score.png?b=master
[ico-pds]: https://img.shields.io/badge/pds-skeleton-blue.svg

[link-monolog]: https://github.com/Seldaek/monolog
[link-container-interop]: https://github.com/container-interop/container-interop
[link-examples]: examples
[link-packagist]: https://packagist.org/packages/nikolaposa/notify
[link-build]: https://travis-ci.org/nikolaposa/notify
[link-code-coverage]: https://scrutinizer-ci.com/g/nikolaposa/notify/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nikolaposa/notify
[link-pds]: https://github.com/php-pds/skeleton
[link-author]: https://github.com/nikolaposa
[link-contributors]: ../../contributors
