<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Notifier\Channel\NotificationSender;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;
use Notifier\Channel\Exception\SendingNotificationFailed;

final class TwilioSmsNotificationSender implements NotificationSender
{
    public const API_BASE_URL = 'https://api.twilio.com';

    /** @var string */
    private $authId;

    /** @var string */
    private $authToken;

    /** @var ClientInterface */
    private $httpClient;

    /** @var SmsMessage */
    private $message;

    public function __construct($authId, $authToken, ClientInterface $httpClient = null)
    {
        $this->authId = $authId;
        $this->authToken = $authToken;

        if (null === $httpClient) {
            $httpClient = new Client();
        }

        $this->httpClient = $httpClient;
    }

    public function send(Notification $notification, Recipient $recipient): void
    {
        if (!$notification instanceof SmsNotification) {
            return;
        }

        $this->message = $notification->toSmsMessage($recipient);
        $this->message->to($recipient->getRecipientContact('sms', $notification));

        $this->doSend($this->buildPayload());
    }

    private function buildPayload(): array
    {
        return [
            'To' => $this->message->to,
            'Body' => $this->message->text,
            'From' => $this->message->from,
        ];
    }

    private function doSend(array $payload): void
    {
        try {
            $this->httpClient->request(
                'POST',
                self::API_BASE_URL . "/2010-04-01/Accounts/{$this->authId}/Messages.json",
                [
                    'auth' => [$this->authId, $this->authToken],
                    'json' => $payload,
                ]
            );
        } catch (GuzzleException $exception) {
            throw SendingNotificationFailed::dueTo($exception, 'sms');
        }
    }
}
