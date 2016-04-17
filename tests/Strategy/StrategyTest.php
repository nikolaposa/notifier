<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Strategy;

use PHPUnit_Framework_TestCase;
use Notify\Strategy\AbstractStrategy;
use Notify\GenericNotification;
use Notify\Tests\TestAsset\Message\DummyMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\GenericContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class StrategyTest extends PHPUnit_Framework_TestCase
{
    public function testFilteringNoRecipientMessages()
    {
        $notification = new GenericNotification([
            new DummyMessage(
                new Recipients([]),
                'test1'
            ),
            new DummyMessage(
                new Recipients([
                    new Actor(new GenericContact('test'))
                ]),
                'test2'
            ),
            new DummyMessage(
                new Recipients([]),
                'test3'
            ),
        ]);

        $strategy = $this->getMockForAbstractClass(AbstractStrategy::class);
        $strategy->expects($this->once())
            ->method('doHandle')
            ->with($this->countOf(1), $this->equalTo($notification));

        $strategy->handle($notification);
    }
}
