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
final class TwilioSMS implements SendServiceInterface
{
    const API_BASE_URL = 'https://api.twilio.com';

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var string
     */
    private $authId;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct($authToken, $authId, ClientInterface $httpClient = null)
    {
        $this->authToken = $authToken;
        $this->authId = $authId;

        if (null === $httpClient) {
            $httpClient = new Client();
        }

        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritDoc}
     */
    public function send(MessageInterface $message)
    {
        if (!$message instanceof SMSMessage) {
            throw UnsupportedMessageException::fromSendServiceAndMessage($this, $message);
        }

        if (!$message->hasSender()) {
            throw new IncompleteMessageException('Message sender is missing');
        }

        $this->doSend($message);
    }

    /**
     * @param SMSMessage $message
     * @return void
     */
    private function doSend(SMSMessage $message)
    {
        foreach ($message->getRecipients() as $recipient) {
            /* @var $recipient ActorInterface */

            $response = $this->httpClient->request(
                'POST',
                self::API_BASE_URL . "/2010-04-01/Accounts/{$this->authId}/Messages.json",
                [
                    'body' => $this->buildPayload($message, $recipient),
                    'auth' => [$this->authToken, $this->authId],
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                ]
            );

            $this->validateResponse($response);
        }
    }

    /**
     * @param SMSMessage $message
     * @param ActorInterface $recipient
     * @return string
     */
    private function buildPayload(SMSMessage $message, ActorInterface $recipient)
    {
        $data = [
            'From' => $message->getSender()->getContact()->getValue(),
            'To' => $recipient->getContact()->getValue(),
            'Body' => $message->getContent(),
        ];

        return json_encode($data);
    }

    /**
     * @param ResponseInterface $response
     * @return void
     * @throws RuntimeException
     */
    private function validateResponse(ResponseInterface $response)
    {
        $status = $response->getStatusCode();

        if ($status >= 200 && $status < 300) {
            return;
        }

        $body = $response->getBody();

        $error = json_decode($body, true);

        if (null === $error) {
            throw new RuntimeException('SMS not sent');
        }

        throw new RuntimeException(
            isset($error['message']) ? $error['message'] : 'SMS not sent',
            isset($error['code']) ? $error['code'] : null
        );
    }
}
