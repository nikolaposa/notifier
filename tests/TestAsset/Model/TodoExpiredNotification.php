<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Model;

use Notifier\Channel\Email\EmailChannel;
use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Sms\SmsChannel;
use Notifier\Channel\Sms\SmsMessage;
use Notifier\Channel\Email\EmailNotification;
use Notifier\Channel\Sms\SmsNotification;
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
            ->from('noreply@test.com')
            ->subject('Todo expired')
            ->body('Todo: <strong>' . $this->todo->getText() . '</strong> has expired')
            ->contentType('text/html');
    }

    public function toSmsMessage(Recipient $recipient): SmsMessage
    {
        return (new SmsMessage())
            ->from('1111')
            ->text('Todo: ' . $this->todo->getText() . ' has expired');
    }
}
