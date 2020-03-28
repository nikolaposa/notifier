<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Notifier\Channel\NotificationSender;
use Notifier\Notification\Notification;
use Notifier\Notification\SmsNotification;
use Notifier\Recipient\Recipient;
use Notifier\Channel\Exception\SendingNotificationFailed;

final class PlivoSmsNotificationSender implements NotificationSender
{
    public const API_BASE_URL = 'https://api.plivo.com';

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

        $payload = $this->buildPayload();

        $this->doSend($payload);
    }

    private function buildPayload()
    {
        return [
            'src' => $this->message->from,
            'dst' => $this->message->to,
            'text' => $this->message->text,
        ];
    }

    private function doSend(array $payload): void
    {
        try {
            $this->httpClient->request(
                'POST',
                self::API_BASE_URL . "/v1/Account/{$this->authId}/Message/",
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
