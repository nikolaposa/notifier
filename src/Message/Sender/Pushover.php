<?php

declare(strict_types=1);

namespace Notify\Message\Sender;

use Notify\Message\PushMessage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Notify\Message\Sender\Exception\UnsupportedMessageException;
use Notify\Message\Sender\Exception\RuntimeException;

final class Pushover implements MessageSenderInterface
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
    
    public function send($message)
    {
        if (! $message instanceof PushMessage) {
            throw UnsupportedMessageException::fromMessageSenderAndMessage($this, $message);
        }

        $this->message = $message;

        $payload = $this->buildPayload();

        $response = $this->executeApiRequest($payload);

        $this->validateResponse($response);
    }

    private function buildPayload()
    {
        return array_merge($this->buildPayloadOptions(), [
            'user' => $this->buildPayloadUser(),
            'message' => $this->buildPayloadMessageString(),
        ]);
    }

    private function buildPayloadOptions()
    {
        $options = $this->message->getOptions()->toArray();

        if (isset($options['device'])) {
            $options['device'] = implode(',', (array) $options['device']);
        }

        return $options;
    }

    private function buildPayloadMessageString()
    {
        $message = $this->message->getMessage();

        $messageLimit = self::MESSAGE_LIMIT;

        if ($this->message->getOptions()->has('title')) {
            $messageLimit -= strlen($this->message->getOptions()->get('title'));
        }

        if (strlen($message) > $messageLimit) {
            $message = substr($message, 0, $messageLimit);
        }

        return $message;
    }

    private function buildPayloadUser()
    {
        return $this->message->getUser()->getContact();
    }

    private function executeApiRequest(array $payload)
    {
        $payload['token'] = $this->apiToken;

        return $this->httpClient->request(
            'POST',
            self::API_BASE_URL . '/1/messages.json',
            [
                'form_params' => $payload,
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
