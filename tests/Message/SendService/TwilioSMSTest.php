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
use Notify\Message\SendService\TwilioSMS;
use GuzzleHttp\ClientInterface;
use Notify\Message\SMSMessage;
use Notify\Contact\PhoneContact;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Tests\TestAsset\Message\DummyMessage;
use GuzzleHttp\Psr7\Response;
use Notify\Message\SendService\Exception\UnsupportedMessageException;
use Notify\Message\SendService\Exception\IncompleteMessageException;
use Notify\Message\SendService\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TwilioSMSTest extends PHPUnit_Framework_TestCase
{
    private function getTwilioSMS(ClientInterface $httpClient = null)
    {
        return new TwilioSMS('token', 'id', $httpClient);
    }

    private function getHttpClientWithSuccessResponse(SMSMessage $message)
    {
        $httpClient = $this->getMock(ClientInterface::class);

        $i = 0;
        foreach ($message->getRecipients() as $recipient) {
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

                        if (!isset($options['json']['From']) || !isset($options['json']['To']) || !isset($options['json']['Body'])) {
                            return false;
                        }

                        if ($options['json']['From'] != $message->getSender()->getContact()->getValue()) {
                            return false;
                        }

                        if ($options['json']['To'] != $recipient->getContact()->getValue()) {
                            return false;
                        }

                        if ($options['json']['Body'] != $message->getContent()) {
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
        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue(new Response(500, [], 'invalid json response')));

        return $httpClient;
    }

    private function getHttpClientWithErrorResponse()
    {
        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue(new Response(403, [], '{"message":"Queue overflow", "code":30001}')));

        return $httpClient;
    }

    public function testSendSuccess()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor(new PhoneContact('+12222222222'))
            ]),
            'test test test',
            new Actor(new PhoneContact('+11111111111'))
        );

        $this->getTwilioSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testMultiSendSuccess()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor(new PhoneContact('+12222222222')),
                new Actor(new PhoneContact('+13333333333')),
                new Actor(new PhoneContact('+14444444444'))
            ]),
            'test test test',
            new Actor(new PhoneContact('+11111111111'))
        );

        $this->getTwilioSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testSendResultsInServerError()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SMS not sent');

        $message = new SMSMessage(
            new Recipients([
                new Actor(new PhoneContact('+12222222222'))
            ]),
            'test test test',
            new Actor(new PhoneContact('+11111111111'))
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
                new Actor(new PhoneContact('+12222222222'))
            ]),
            'test test test',
            new Actor(new PhoneContact('+11111111111'))
        );

        $this->getTwilioSMS($this->getHttpClientWithErrorResponse())->send($message);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(UnsupportedMessageException::class);

        $message = new DummyMessage(
            new Recipients([
                new Actor(new PhoneContact('+12222222222'))
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
                new Actor(new PhoneContact('+12222222222'))
            ]),
            'test test test'
        );

        $this->getTwilioSMS()->send($message);
    }
}
