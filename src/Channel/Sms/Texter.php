<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

use Notifier\Exception\SendingMessageFailed;

interface Texter
{
    /**
     * @throws SendingMessageFailed
     */
    public function send(SmsMessage $message): void;
}
