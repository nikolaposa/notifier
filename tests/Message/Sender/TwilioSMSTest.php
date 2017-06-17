<?php

declare(strict_types=1);

namespace Notify\Tests\Message\Sender;

use PHPUnit\Framework\TestCase;
use Notify\Message\Sender\TwilioSMS;
use GuzzleHttp\ClientInterface;
use Notify\Message\SMSMessage;
use Notify\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Tests\TestAsset\Message\DummyMessage;
use GuzzleHttp\Psr7\Response;
use Notify\Message\Sender\Exception\UnsupportedMessageException;
use Notify\Message\Sender\Exception\IncompleteMessageException;
use Notify\Message\Sender\Exception\RuntimeException;

class TwilioSMSTest extends TestCase
{
    private function getTwilioSMS(ClientInterface $httpClient = null)
    {
        return new TwilioSMS('token', 'id', $httpClient);
    }

    private function getHttpClientWithSuccessResponse(SMSMessage $message)
    {
        $httpClient = $this->createMock(ClientInterface::class);

        $i = 0;
        foreach ($message->getRecipients() as $recipient) {
            /* @var $recipient \Notify\Message\Actor\ActorInterface */
            
            $httpClient->expects($this->at($i++))
                ->method('request')
                ->with(
                    $this->equalTo('POST'),
                    $this->callback(function ($url) {
                        return preg_match('[' . TwilioSMS::API_BASE_URL . '|id]', $url);
                    }),
                    $this->callback(function ($options) use ($message, $recipient) {
                        if (!is_array($options)) {
                            return false;
                        }

                        if (!isset($options['auth'])) {
                            return false;
                        }

                        if (!isset($options['json'])) {
                            return false;
                        }

                        if (!isset($options['json']['From'], $options['json']['To'], $options['json']['Body'])) {
                            return false;
                        }

                        if ($options['json']['From'] !== $message->getFrom()->getContact()) {
                            return false;
                        }

                        if ($options['json']['To'] !== $recipient->getContact()) {
                            return false;
                        }

                        if ($options['json']['Body'] !== $message->getContent()) {
                            return false;
                        }

                        return true;
                    })
                )
                ->will($this->returnValue(new Response(204)));
        }

        return $httpClient;
    }

    private function getHttpClientWithInvalidResponse()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue(new Response(500, [], 'invalid json response')));

        return $httpClient;
    }

    private function getHttpClientWithErrorResponse()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue(new Response(403, [], '{"message":"Queue overflow", "code":30001}')));

        return $httpClient;
    }

    public function testSendSuccess()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getTwilioSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testMultiSendSuccess()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222'),
                new Actor('+13333333333'),
                new Actor('+14444444444')
            ]),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getTwilioSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testSendResultsInServerError()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SMS not sent');

        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getTwilioSMS($this->getHttpClientWithInvalidResponse())->send($message);
    }

    public function testSendResultsInClientError()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Queue overflow');
        $this->expectExceptionCode(30001);

        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getTwilioSMS($this->getHttpClientWithErrorResponse())->send($message);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(UnsupportedMessageException::class);

        $message = new DummyMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            'test test test'
        );

        $this->getTwilioSMS()->send($message);
    }

    public function testExceptionIsRaisedIfMessageSenderIsMissing()
    {
        $this->expectException(IncompleteMessageException::class);
        $this->expectExceptionMessage('Message sender is missing');

        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            'test test test'
        );

        $this->getTwilioSMS()->send($message);
    }
}
