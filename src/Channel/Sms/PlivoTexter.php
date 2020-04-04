<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Notifier\Exception\SendingMessageFailed;

final class PlivoTexter implements Texter
{
    public const API_BASE_URL = 'https://api.plivo.com';

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
                self::API_BASE_URL . "/v1/Account/{$this->authId}/Message/",
                [
                    RequestOptions::AUTH => [$this->authId, $this->authToken],
                    RequestOptions::JSON => [
                        'src' => $message->from,
                        'dst' => $message->to,
                        'text' => $message->text,
                    ],
                ]
            );
        } catch (GuzzleException $exception) {
            throw SendingMessageFailed::dueTo($exception);
        }
    }
}
