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
use Notify\Message\PushMessage;
use Notify\Message\Actor\ActorInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Notify\Message\SendService\Exception\UnsupportedMessageException;
use Notify\Message\SendService\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Pushover implements SendServiceInterface
{
    const API_BASE_URL = 'https://api.pushover.net';

    const MESSAGE_LIMIT = 512;

    /**
     * @var string
     */
    private $apiToken;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct($apiToken, ClientInterface $httpClient = null)
    {
        $this->apiToken = $apiToken;

        if (null === $httpClient) {
            $httpClient = new Client();
        }

        $this->httpClient = $httpClient;
    }

    public function send(MessageInterface $message)
    {
        if (!$message instanceof PushMessage) {
            throw UnsupportedMessageException::fromSendServiceAndMessage($this, $message);
        }

        $this->doSend($message);
    }

    /**
     * @param PushMessage $message
     * @return void
     */
    private function doSend(PushMessage $message)
    {
        foreach ($message->getRecipients() as $recipient) {
            $response = $this->httpClient->request(
                'POST',
                self::API_BASE_URL . '/1/messages.json',
                [
                    'form_params' => $this->buildPayload($message, $recipient),
                ]
            );

            $this->validateResponse($response);
        }
    }

    /**
     * @param PushMessage $message
     * @return array
     */
    private function buildPayload(PushMessage $message, ActorInterface $recipient)
    {
        $data = [
            'token' => $this->apiToken,
            'user' => $recipient->getContact()->getValue(),
            'message' => $message->getContent(),
        ];

        $options = $message->getOptions()->toArray();

        if (isset($options['device'])) {
            $data['device'] = implode(',', (array) $options['device']);
            unset($options['device']);
        }

        $messageLimit = self::MESSAGE_LIMIT;

        if (isset($options['title'])) {
            $messageLimit -= strlen($options['title']);
        }

        if (strlen($data['message']) > $messageLimit) {
            $data['message'] = substr($data['message'], 0, $messageLimit);
        }

        $data = array_merge($data, $options);

        return $data;
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

        throw new RuntimeException('Push message not sent');
    }
}
