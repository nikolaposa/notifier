# Notifier

[![Build Status][ico-build]][link-build]
[![Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Latest Version][ico-version]][link-packagist]
[![PDS Skeleton][ico-pds]][link-pds]

Extensible library for building notifications and sending them via different delivery channels.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install 
the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/notifier
```

## Theory of operation

Notifications are informative messages that are sent through different channels (i.e. web, SMS, mobile push) to notify 
users of something that has happened in the application. Notification is a higher-level abstraction, a concept that 
encapsulates a subject to be notified to the recipient, regardless of delivery channels through which that information 
can be communicated. From an architectural standpoint, notification is a domain concern.

In order to minimize the coupling of your domain with the infrastructure for sending notifications, Notifier library was 
based on on unobtrusive interfaces that should be implemented by your objects in order to plug them into the workflow of 
the library. Those are:

1. `Notification` - marks the notification object as such and provides list of channels through which Notification can 
be sent,
2. `Recipient` - represents the recipient of the notification which provides contact (i.e. email address, phone number) 
for a certain channel; typically implemented by a User domain object.

Also, for each channel through which Notification is supposed to be sent, Notification class should implement 
channel-specific Notification interface, for example `EmailNotification`, that is used to cast the Notification to a 
Message that represents transport-level object Notification gets converted into for sending. These channel-specific 
Notification interfaces extend the `Notification` interface itself, so you do not need to implement it explicitly.

Channel component captures implementation details of how a Notification is sent via certain delivery channels. Specific 
channel implementation typically consists of:

1. channel-specific Notification interface,
2. Message class,
3. `Channel` implementation responsible for the very act of sending the Notification.

Out of the box, this library features facilities for sending notifications via email and SMS. The highly extensible 
design allows for implementing custom delivery channels.

Finally, `Notifier` service is a facade that manages the entire process of sending a Notification to a list of 
Recipients via supported channels. It is the only service of this library that the calling code is supposed to interact 
with directly.

## Usage

**Creating Notifications**

```php
namespace App\Model;

use Notifier\Channel\Email\EmailChannel;
use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Sms\SmsChannel;
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
    
    public function getSupportedChannels(): array
    {
        return [EmailChannel::NAME, SmsChannel::NAME];
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

**Implementing Recipient**

```php
namespace App\Model;

class User implements Recipient
{
    /** @var string */
    protected $name;

    /** @var array */
    protected $contacts;

    public function __construct(string $name, array $contacts)
    {
        $this->name = $name;
        $this->contacts = $contacts;
    }
    
    public funtion getName(): string
    {
        return $this->name;
    }

    public function getRecipientContact(string $channel, Notification $notification): ?string
    {
        return $this->contacts[$channel] ?? null;
    }
    
    public function getRecipientName(): string
    {
        return $this->name;
    }
}
```

**Sending Notifications**

```php
use Notifier\Channel\ChannelManager;
use Notifier\Channel\Email\EmailChannel;
use Notifier\Channel\Email\SimpleMailer;
use Notifier\Channel\Sms\SmsChannel;
use Notifier\Channel\Sms\TwilioTexter;
use Notifier\Notifier;
use Notifier\Recipient\Recipients;

$notifier = new Notifier(new ChannelManager(
    new EmailChannel(new SimpleMailer()),
    new SmsChannel(new TwilioTexter('auth_id', 'auth_token'))
));

$notifier->send(new TodoExpiredNotification($todo), new Recipients($user));
```

## Credits

- [Nikola Poša][link-author]
- [All Contributors][link-contributors]

## License

Released under MIT License - see the [License File](LICENSE) for details.


[ico-version]: https://poser.pugx.org/nikolaposa/notifier/v/stable
[ico-build]: https://travis-ci.org/nikolaposa/notifier.svg?branch=master
[ico-code-coverage]: https://scrutinizer-ci.com/g/nikolaposa/notifier/badges/coverage.png?b=master
[ico-code-quality]: https://scrutinizer-ci.com/g/nikolaposa/notifier/badges/quality-score.png?b=master
[ico-pds]: https://img.shields.io/badge/pds-skeleton-blue.svg

[link-packagist]: https://packagist.org/packages/nikolaposa/notifier
[link-build]: https://travis-ci.org/nikolaposa/notifier
[link-code-coverage]: https://scrutinizer-ci.com/g/nikolaposa/notifier/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nikolaposa/notifier
[link-pds]: https://github.com/php-pds/skeleton
[link-author]: https://github.com/nikolaposa
[link-contributors]: ../../contributors
