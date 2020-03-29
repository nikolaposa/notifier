<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Model;

use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Email\EmailNotification;
use Notifier\Recipient\Recipient;

class TodoReminderNotification implements EmailNotification
{
    /** @var Todo */
    protected $todo;

    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }

    public function getSupportedChannels(): array
    {
        return ['email'];
    }

    public function toEmailMessage(Recipient $recipient): EmailMessage
    {
        return (new EmailMessage())
            ->subject('Todo reminder')
            ->body('Reminder for todo:' . $this->todo->getText());
    }
}
