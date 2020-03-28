<?php

declare(strict_types=1);

namespace Notifier\Tests\Message\Sender;

use PHPUnit\Framework\TestCase;
use Notifier\Channel\Sms\PlivoSmsNotificationSender;
use GuzzleHttp\ClientInterface;
use Notifier\Channel\Sms\SmsMessage;
use Notifier\Message\Actor\Actor;
use Notifier\Tests\TestAsset\Message\DummyMessage;
use GuzzleHttp\Psr7\Response;
use Notifier\Channel\Exception\UnsupportedMessage;
use Notifier\Channel\Exception\IncompleteMessage;
use Notifier\Channel\Exception\SendingNotificationFailed;

class PlivoSmsNotificationSenderTest extends TestCase
{
    private function getPlivoSMS(ClientInterface $httpClient = null)
    {
        return new PlivoSmsNotificationSender('token', 'id', $httpClient);
    }

    private function getHttpClientWithSuccessResponse(SmsMessage $message)
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->callback(function ($url) {
                    return preg_match('[' . PlivoSmsNotificationSender::API_BASE_URL . '|id]', $url);
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

                    if (!isset($options['json']['src'], $options['json']['dst'], $options['json']['text'])) {
                        return false;
                    }

                    if ($options['json']['src'] !== $message->getFrom()->getContact()) {
                        return false;
                    }

                    if ($options['json']['dst'] !== $message->getTo()->getContact()) {
                        return false;
                    }

                    if ($options['json']['text'] != $message->getText()) {
                        return false;
                    }

                    return true;
                })
            )
            ->will($this->returnValue(new Response(202)));

        return $httpClient;
    }

    private function getHttpClientWithErrorResponse()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue(new Response(403)));

        return $httpClient;
    }

    public function testSendSuccess()
    {
        $message = new SmsMessage(
            new Actor('+12222222222'),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getPlivoSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testSendResultsInError()
    {
        $this->expectException(SendingNotificationFailed::class);
        $this->expectExceptionMessage('SMS not sent');

        $message = new SmsMessage(
            new Actor('+12222222222'),
            'test test test',
            new Actor('+11111111111')
        );

        $this->getPlivoSMS($this->getHttpClientWithErrorResponse())->send($message);
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

        $this->getPlivoSMS()->send($message);
    }

    public function testExceptionIsRaisedIfMessageSenderIsMissing()
    {
        $this->expectException(IncompleteMessage::class);
        $this->expectExceptionMessage('Message sender is missing');

        $message = new SmsMessage(
            new Actor('+12222222222'),
            'test test test'
        );

        $this->getPlivoSMS()->send($message);
    }
}
