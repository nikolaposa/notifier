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

    /**
     * @var PushMessage
     */
    private $message;

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

        $this->message = $message;

        $parameters = $this->buildParameters();

        foreach ($this->message->getRecipients() as $recipient) {
            $parameters = $this->addUserParameter($parameters, $recipient);

            $response = $this->executeApiRequest($parameters);

            $this->validateResponse($response);
        }
    }

    private function buildParameters()
    {
        return array_merge($this->buildOptions(), [
            'token' => $this->apiToken,
            'message' => $this->buildMessageString(),
        ]);
    }

    private function buildOptions()
    {
        $options = $this->message->getOptions()->toArray();

        if (isset($options['device'])) {
            $options['device'] = implode(',', (array) $options['device']);
        }

        return $options;
    }

    private function buildMessageString()
    {
        $message = $this->message->getContent();

        $messageLimit = self::MESSAGE_LIMIT;

        if ($this->message->getOptions()->has('title')) {
            $messageLimit -= strlen($this->message->getOptions()->get('title'));
        }

        if (strlen($message) > $messageLimit) {
            $message = substr($message, 0, $messageLimit);
        }

        return $message;
    }

    private function addUserParameter(array $parameters, ActorInterface $recipient)
    {
        $parameters['user'] = $recipient->getContact()->getValue();

        return $parameters;
    }

    private function executeApiRequest(array $parameters)
    {
        return $this->httpClient->request(
            'POST',
            self::API_BASE_URL . '/1/messages.json',
            [
                'form_params' => $parameters,
            ]
        );
    }

    private function validateResponse(ResponseInterface $response)
    {
        $status = $response->getStatusCode();

        if ($status >= 200 && $status < 300) {
            return;
        }

        throw new RuntimeException('Push message not sent');
    }
}
