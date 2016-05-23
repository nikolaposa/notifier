<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\SendService;

use Notify\Message\MessageInterface;
use Notify\Message\SMSMessage;
use Notify\Message\Actor\ActorInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Notify\Message\SendService\Exception\UnsupportedMessageException;
use Notify\Message\SendService\Exception\IncompleteMessageException;
use Notify\Message\SendService\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class PlivoSMS implements SendServiceInterface
{
    const API_BASE_URL = 'https://api.plivo.com';

    /**
     * @var string
     */
    private $authId;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var SMSMessage
     */
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

    public function send(MessageInterface $message)
    {
        if (!$message instanceof SMSMessage) {
            throw UnsupportedMessageException::fromSendServiceAndMessage($this, $message);
        }

        if (!$message->hasSender()) {
            throw new IncompleteMessageException('Message sender is missing');
        }

        $this->message = $message;

        $payload = $this->buildPayload();

        $response = $this->executeApiRequest($payload);

        $this->validateResponse($response);
    }

    private function buildPayload()
    {
        return [
            'auth' => [$this->authId, $this->authToken],
            'json' => [
                'src' => $this->buildSourceString(),
                'dst' => $this->buildDestinationString(),
                'text' => $this->buildText(),
            ],
        ];
    }

    private function buildSourceString()
    {
        return $this->message->getSender()->getContact()->getValue();
    }

    private function buildDestinationString()
    {
        $dst = [];
        foreach ($this->message->getRecipients() as $recipient) {
            /* @var $recipient ActorInterface */
            $dst[] = $recipient->getContact()->getValue();
        }

        return implode('<', $dst);
    }

    private function buildText()
    {
        return $this->message->getContent();
    }

    private function executeApiRequest(array $payload)
    {
        return $this->httpClient->request(
            'POST',
            self::API_BASE_URL . "/v1/Account/{$this->authId}/Message/",
            $payload
        );
    }

    private function validateResponse(ResponseInterface $response)
    {
        $status = $response->getStatusCode();

        if ($status >= 200 && $status < 300) {
            return;
        }

        throw new RuntimeException('SMS not sent');
    }
}
