<?php

declare(strict_types=1);

namespace Notifier\Tests\Message\Sender;

use PHPUnit\Framework\TestCase;
use Notifier\Channel\Push\PushoverNotificationSender;
use GuzzleHttp\ClientInterface;
use Notifier\Channel\Push\PushMessage;
use Notifier\Message\Actor\Actor;
use Notifier\Tests\TestAsset\Message\DummyMessage;
use GuzzleHttp\Psr7\Response;
use Notifier\Channel\Options;
use Notifier\Channel\Exception\UnsupportedMessage;
use Notifier\Channel\Exception\SendingNotificationFailed;

class PushoverNotificationSenderTest extends TestCase
{
    private function getPushover(ClientInterface $httpClient = null)
    {
        return new PushoverNotificationSender('token', $httpClient);
    }

    private function getHttpClientWithSuccessResponse(PushMessage $message)
    {
        $httpClient = $this->createMock(ClientInterface::class);

        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->stringContains(PushoverNotificationSender::API_BASE_URL),
                $this->callback(function ($options) use ($message) {
                    if (!is_array($options)) {
                        return false;
                    }

                    if (!isset($options['form_params'])) {
                        return false;
                    }

                    if (!isset($options['form_params']['token'], $options['form_params']['user'], $options['form_params']['message'])) {
                        return false;
                    }

                    if ($options['form_params']['token'] !== 'token') {
                        return false;
                    }

                    if ($options['form_params']['user'] !== $message->getUser()->getContact()) {
                        return false;
                    }

                    if ($options['form_params']['message'] !== $message->getMessage()) {
                        return false;
                    }

                    return true;
                })
            )
            ->will($this->returnValue(new Response(200)));

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
        $message = new PushMessage(
            new Actor('11111111111'),
            'test test test'
        );

        $this->getPushover($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testSendingWithOptions()
    {
        $message = new PushMessage(
            new Actor('11111111111'),
            'test test test',
            new Options([
                'title' => 'test',
                'sound' => 'example',
            ])
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->stringContains(PushoverNotificationSender::API_BASE_URL),
                $this->callback(function ($options) {
                    return $options['form_params']['title'] === 'test'
                        && $options['form_params']['sound'] === 'example';
                })
            )
            ->will($this->returnValue(new Response(200)));

        $this->getPushover($httpClient)->send($message);
    }

    public function testSendingDeviceOption()
    {
        $message = new PushMessage(
            new Actor('11111111111'),
            'test test test',
            new Options([
                'device' => [
                    'iphone',
                    'nexus5'
                ],
            ])
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->stringContains(PushoverNotificationSender::API_BASE_URL),
                $this->callback(function ($options) {
                    return isset($options['form_params']['device'])
                        && $options['form_params']['device'] === 'iphone,nexus5';
                })
            )
            ->will($this->returnValue(new Response(200)));

        $this->getPushover($httpClient)->send($message);
    }

    public function testLimitingMessageLength()
    {
        $content = str_pad('test', 600, ' test');

        $message = new PushMessage(
            new Actor('11111111111'),
            $content
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->stringContains(PushoverNotificationSender::API_BASE_URL),
                $this->callback(function ($options) use ($content) {
                    return $options['form_params']['message'] !== $content
                        && strlen($options['form_params']['message']) === PushoverNotificationSender::MESSAGE_LIMIT;
                })
            )
            ->will($this->returnValue(new Response(200)));

        $this->getPushover($httpClient)->send($message);
    }

    public function testLimitingMessageLengthTakingTitleIntoAccount()
    {
        $content = str_pad('test', 400, ' test');
        $title = str_pad('title', 200, ' title');

        $message = new PushMessage(
            new Actor('11111111111'),
            $content,
            new Options(['title' => $title])
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->stringContains(PushoverNotificationSender::API_BASE_URL),
                $this->callback(function ($options) {
                    return strlen($options['form_params']['message'] . $options['form_params']['title']) === PushoverNotificationSender::MESSAGE_LIMIT;
                })
            )
            ->will($this->returnValue(new Response(200)));

        $this->getPushover($httpClient)->send($message);
    }

    public function testSendResultsInError()
    {
        $this->expectException(SendingNotificationFailed::class);
        $this->expectExceptionMessage('Push message not sent');

        $message = new PushMessage(
            new Actor('11111111111'),
            'test test test'
        );

        $this->getPushover($this->getHttpClientWithErrorResponse())->send($message);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(UnsupportedMessage::class);

        $message = new DummyMessage(
            [
                new Actor('11111111111')
            ],
            'test test test'
        );

        $this->getPushover()->send($message);
    }
}
