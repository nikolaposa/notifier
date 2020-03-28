<?php

declare(strict_types=1);

namespace Notifier\Tests\Message\Sender;

use PHPUnit\Framework\TestCase;
use Notifier\Channel\Sms\TwilioSmsNotificationSender;
use GuzzleHttp\ClientInterface;
use Notifier\Channel\Sms\SmsMessage;
use Notifier\Message\Actor\Actor;
use Notifier\Tests\TestAsset\Message\DummyMessage;
use GuzzleHttp\Psr7\Response;
use Notifier\Channel\Exception\UnsupportedMessage;
use Notifier\Channel\Exception\IncompleteMessage;
use Notifier\Channel\Exception\SendingNotificationFailed;

class TwilioSmsNotificationSenderTest extends TestCase
{
    private function getTwilioSMS(ClientInterface $httpClient = null)
    {
        return new TwilioSmsNotificationSender('token', 'id', $httpClient);
    }

    private function getHttpClientWithSuccessResponse(SmsMessage $message)
    {
        $httpClient = $this->createMock(ClientInterface::class);

        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->callback(function ($url) {
                    return preg_match('[' . TwilioSmsNotificationSender::API_BASE_URL . '|id]', $url);
                }),
                $this->callback(function ($options) use ($message) {
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

                    if ($options['json']['To'] !== $message->getTo()->getContact()) {
                        return false;
                    }

                    if ($options['json']['Body'] !== $message->getText()) {
                        return false;
                    }

                    return true;
                })
            )
            ->will($this->returnValue(new Response(204)));

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
        $message = new SmsMessage(
            new Actor('+12222222222'),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getTwilioSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testSendResultsInServerError()
    {
        $this->expectException(SendingNotificationFailed::class);
        $this->expectExceptionMessage('SMS not sent');

        $message = new SmsMessage(
            new Actor('+12222222222'),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getTwilioSMS($this->getHttpClientWithInvalidResponse())->send($message);
    }

    public function testSendResultsInClientError()
    {
        $this->expectException(SendingNotificationFailed::class);
        $this->expectExceptionMessage('Queue overflow');
        $this->expectExceptionCode(30001);

        $message = new SmsMessage(
            new Actor('+12222222222'),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getTwilioSMS($this->getHttpClientWithErrorResponse())->send($message);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(UnsupportedMessage::class);

        $message = new DummyMessage(
            [
                new Actor('+12222222222')
            ],
            'test test test'
        );

        $this->getTwilioSMS()->send($message);
    }

    public function testExceptionIsRaisedIfMessageSenderIsMissing()
    {
        $this->expectException(IncompleteMessage::class);
        $this->expectExceptionMessage('Message sender is missing');

        $message = new SmsMessage(
            new Actor('+12222222222'),
            'test test test'
        );

        $this->getTwilioSMS()->send($message);
    }
}
