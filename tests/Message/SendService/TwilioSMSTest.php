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

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TwilioSMSTest extends PHPUnit_Framework_TestCase
{
    private function getTwilioSMS(ClientInterface $httpClient = null)
    {
        return new TwilioSMS('token', 'id', $httpClient);
    }

    public function testSendSuccess()
    {
        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->callback(function($url) {
                    return preg_match('[' . TwilioSMS::API_BASE_URL . '|id]', $url);
                }),
                $this->callback(function($options) {
                    return is_array($options)
                        && isset($options['body'])
                        && preg_match('[\+11111111111|\+1222222222|test test test]', $options['body'])
                        && isset($options['auth']);
                })
            )
            ->will($this->returnValue(new Response(204)));

        $message = new SMSMessage(
            new Recipients([
                new Actor(new PhoneContact('+11111111111'))
            ]),
            'test test test',
            new Actor(new PhoneContact('+12222222222'))
        );

        $this->getTwilioSMS($httpClient)->send($message);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(UnsupportedMessageException::class);

        $message = new DummyMessage(
            new Recipients([
                new Actor(new PhoneContact('+11111111111'))
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
                new Actor(new PhoneContact('+11111111111'))
            ]),
            'test test test'
        );

        $this->getTwilioSMS()->send($message);
    }
}
