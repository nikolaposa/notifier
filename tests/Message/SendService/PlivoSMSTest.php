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
use Notify\Message\SendService\PlivoSMS;
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
class PlivoSMSTest extends PHPUnit_Framework_TestCase
{
    private function getPlivoSMS(ClientInterface $httpClient = null)
    {
        return new PlivoSMS('token', 'id', $httpClient);
    }

    private function getHttpClientWithSuccessResponse(SMSMessage $message)
    {
        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->callback(function ($url) {
                    return preg_match('[' . PlivoSMS::API_BASE_URL . '|id]', $url);
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

                    if (!isset($options['json']['src']) || !isset($options['json']['dst']) || !isset($options['json']['text'])) {
                        return false;
                    }

                    if ($options['json']['src'] != $message->getSender()->getContact()->getValue()) {
                        return false;
                    }

                    $to = [];
                    foreach ($message->getRecipients() as $recipient) {
                        $to[] = $recipient->getContact()->getValue();
                    }

                    if ($options['json']['dst'] != implode('<', $to)) {
                        return false;
                    }

                    if ($options['json']['text'] != $message->getContent()) {
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
        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue(new Response(403)));

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

        $this->getPlivoSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
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

        $this->getPlivoSMS($this->getHttpClientWithSuccessResponse($message))->send($message);
    }

    public function testSendResultsInError()
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

        $this->getPlivoSMS($this->getHttpClientWithErrorResponse())->send($message);
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

        $this->getPlivoSMS()->send($message);
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

        $this->getPlivoSMS()->send($message);
    }
}
