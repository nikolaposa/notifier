<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Notifier\Channel\Exception\SendingMessageFailed;

final class TwilioTexter implements Texter
{
    public const API_BASE_URL = 'https://api.twilio.com';

    /** @var string */
    private $authId;

    /** @var string */
    private $authToken;

    /** @var ClientInterface */
    private $httpClient;

    public function __construct(string $authId, string $authToken, ClientInterface $httpClient = null)
    {
        $this->authId = $authId;
        $this->authToken = $authToken;

        if (null === $httpClient) {
            $httpClient = new Client();
        }

        $this->httpClient = $httpClient;
    }

    public function send(SmsMessage $message): void
    {
        try {
            $this->httpClient->post(
                self::API_BASE_URL . "/2010-04-01/Accounts/{$this->authId}/Messages.json",
                [
                    RequestOptions::AUTH => [$this->authId, $this->authToken],
                    RequestOptions::FORM_PARAMS => [
                        'Body' => $message->text,
                        'From' => $message->from,
                        'To' => $message->to,
                    ],
                ]
            );
        } catch (GuzzleException $exception) {
            throw SendingMessageFailed::dueTo($exception);
        }
    }
}
