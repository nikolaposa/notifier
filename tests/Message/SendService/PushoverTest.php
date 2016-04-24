<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Message\SendService;

use PHPUnit_Framework_TestCase;
use Notify\Message\SendService\Pushover;
use GuzzleHttp\ClientInterface;
use Notify\Message\PushMessage;
use Notify\Contact\MobileDeviceContact;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Tests\TestAsset\Message\DummyMessage;
use GuzzleHttp\Psr7\Response;
use Notify\Message\Options\Options;
use Notify\Message\SendService\Exception\UnsupportedMessageException;
use Notify\Message\SendService\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PushoverTest extends PHPUnit_Framework_TestCase
{
    private function getPushover(ClientInterface $httpClient = null)
    {
        return new Pushover('token', $httpClient);
    }

    private function getHttpClientWithSuccessResponse(PushMessage $message)
    {
        $httpClient = $this->getMock(ClientInterface::class);

        $i = 0;
        foreach ($message->getRecipients() as $recipient) {
            $httpClient->expects($this->at($i++))
                ->method('request')
                ->with(
                    $this->equalTo('POST'),
                    $this->stringContains(Pushover::API_BASE_URL),
                    $this->callback(function ($options) use ($message, $recipient) {
                        if (!is_array($options)) {
                            return false;
                        }

                        if (!isset($options['form_params'])) {
                            return false;
                        }

                        if (
                            !isset($options['form_params']['token'])
                            || !isset($options['form_params']['user'])
                            || !isset($options['form_params']['message'])
                        ) {
                            return false;
                        }

                        if ($options['form_params']['token'] != 'token') {
                            return false;
                        }

                        if ($options['form_params']['user'] != $recipient->getContact()->getValue()) {
                            return false;
                        }

                        if ($options['form_params']['message'] != $message->getContent()) {
                            return false;
                        }

                        return true;
                    })
                )
                ->will($this->returnValue(new Response(200)));
        }

        return $httpClient;
    }

    private function getHttpClientWithErrorResponse()
    {
        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue(new Response(403)));

        return $httpClient;
    }

    public function testSendSuccess()
    {
        $message = new PushMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('11111111111'))
            ]),
            'test test test'
        );

        $this->getPushover($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testMultiSendSuccess()
    {
        $message = new PushMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('11111111111')),
                new Actor(new MobileDeviceContact('22222222222')),
                new Actor(new MobileDeviceContact('33333333333'))
            ]),
            'test test test'
        );

        $this->getPushover($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testSendingDeviceOption()
    {
        $message = new PushMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('11111111111'))
            ]),
            'test test test',
            new Options([
                'device' => [
                    'iphone',
                    'nexus5'
                ],
            ])
        );

        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
                ->method('request')
                ->with(
                    $this->equalTo('POST'),
                    $this->stringContains(Pushover::API_BASE_URL),
                    $this->callback(function ($options) {
                        return isset($options['form_params']['device'])
                            && $options['form_params']['device'] == 'iphone,nexus5';
                    })
                )
                ->will($this->returnValue(new Response(200)));

        $this->getPushover($httpClient)->send($message);
    }

    public function testLimitingMessageLength()
    {
        $content = str_pad('test', 600, ' test');

        $message = new PushMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('11111111111'))
            ]),
            $content
        );

        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
                ->method('request')
                ->with(
                    $this->equalTo('POST'),
                    $this->stringContains(Pushover::API_BASE_URL),
                    $this->callback(function ($options) use ($content) {
                        return $options['form_params']['message'] != $content
                            && strlen($options['form_params']['message']) == Pushover::MESSAGE_LIMIT;
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
            new Recipients([
                new Actor(new MobileDeviceContact('11111111111'))
            ]),
            $content,
            new Options(['title' => $title])
        );

        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
                ->method('request')
                ->with(
                    $this->equalTo('POST'),
                    $this->stringContains(Pushover::API_BASE_URL),
                    $this->callback(function ($options) {
                        return strlen($options['form_params']['message'] . $options['form_params']['title']) == Pushover::MESSAGE_LIMIT;
                    })
                )
                ->will($this->returnValue(new Response(200)));

        $this->getPushover($httpClient)->send($message);
    }

    public function testSendResultsInError()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Push message not sent');

        $message = new PushMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('11111111111'))
            ]),
            'test test test'
        );

        $this->getPushover($this->getHttpClientWithErrorResponse())->send($message);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(UnsupportedMessageException::class);

        $message = new DummyMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('11111111111'))
            ]),
            'test test test'
        );

        $this->getPushover()->send($message);
    }
}
