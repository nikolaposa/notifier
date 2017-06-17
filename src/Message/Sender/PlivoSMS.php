<?php

declare(strict_types=1);

namespace Notify\Message\Sender;

use Notify\Message\SMSMessage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Notify\Message\Sender\Exception\UnsupportedMessageException;
use Notify\Message\Sender\Exception\IncompleteMessageException;
use Notify\Message\Sender\Exception\RuntimeException;

final class PlivoSMS implements MessageSenderInterface
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

    /**
     * {@inheritdoc}
     */
    public function send($message)
    {
        $this->setMessage($message);

        $payload = $this->buildPayload();

        $response = $this->executeApiRequest($payload);

        $this->validateResponse($response);
    }

    private function setMessage($message)
    {
        if (! $message instanceof SMSMessage) {
            throw UnsupportedMessageException::fromMessageSenderAndMessage($this, $message);
        }

        if (! $message->hasFrom()) {
            throw new IncompleteMessageException('Message sender is missing');
        }

        $this->message = $message;
    }

    private function buildPayload()
    {
        return [
            'src' => $this->buildPayloadSourceString(),
            'dst' => $this->buildPayloadDestinationString(),
            'text' => $this->buildPayloadText(),
        ];
    }

    private function buildPayloadSourceString()
    {
        return $this->message->getFrom()->getContact();
    }

    private function buildPayloadDestinationString()
    {
        return $this->message->getTo()->getContact();
    }

    private function buildPayloadText()
    {
        return $this->message->getText();
    }

    private function executeApiRequest(array $payload)
    {
        return $this->httpClient->request(
            'POST',
            self::API_BASE_URL . "/v1/Account/{$this->authId}/Message/",
            [
                'auth' => [$this->authId, $this->authToken],
                'json' => $payload,
            ]
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
