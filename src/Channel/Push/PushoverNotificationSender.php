<?php

declare(strict_types=1);

namespace Notifier\Channel\Push;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use Notifier\Channel\NotificationSender;
use Notifier\Notification\Notification;
use Notifier\Notification\PushNotification;
use Notifier\Recipient\Recipient;
use Psr\Http\Message\ResponseInterface;
use Notifier\Channel\Exception\SendingNotificationFailed;

final class PushoverNotificationSender implements NotificationSender
{
    public const API_BASE_URL = 'https://api.pushover.net';

    private const MESSAGE_LIMIT = 512;

    /** @var string */
    private $apiToken;

    /** @var ClientInterface */
    private $httpClient;

    /** @var PushMessage */
    private $message;

    public function __construct($apiToken, ClientInterface $httpClient = null)
    {
        $this->apiToken = $apiToken;

        if (null === $httpClient) {
            $httpClient = new Client();
        }

        $this->httpClient = $httpClient;
    }

    public function send(Notification $notification, Recipient $recipient): void
    {
        if (!$notification instanceof PushNotification) {
            return;
        }

        $this->message = $notification->toPushMessage($recipient);
        $this->message->to($recipient->getRecipientContact('push', $notification));

        $payload = $this->buildPayload();

        $this->doSend($payload);
    }

    private function buildPayload()
    {
        return array_merge($this->buildPayloadOptions(), [
            'user' => $this->buildPayloadUser(),
            'message' => $this->buildPayloadMessageString(),
        ]);
    }

    private function buildPayloadOptions(): array
    {
        $options = [];

        if ([] !== $this->message->devices) {
            $options['device'] = implode(',', $this->message->devices);
        }

        return $options;
    }

    private function buildPayloadMessageString(): string
    {
        $message = $this->message->message;

        $messageLimit = self::MESSAGE_LIMIT;

        if ('' !== $this->message->title) {
            $messageLimit -= strlen($this->message->title);
        }

        if (strlen($message) > $messageLimit) {
            $message = substr($message, 0, $messageLimit);
        }

        return $message;
    }

    private function buildPayloadUser(): string
    {
        return $this->message->to;
    }

    private function doSend(array $payload): void
    {
        $payload['token'] = $this->apiToken;

        try {
            $response = $this->httpClient->request(
                'POST',
                self::API_BASE_URL . '/1/messages.json',
                [
                    'form_params' => $payload,
                ]
            );

            $status = $response->getStatusCode();

            if ($status < 200 || $status >= 300) {
                throw new SendingNotificationFailed('Push message not sent');
            }
        } catch (GuzzleException $exception) {
            throw new SendingNotificationFailed('Push message not sent');
        }
    }
}
