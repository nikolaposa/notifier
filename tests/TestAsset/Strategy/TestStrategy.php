<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\TestAsset\Strategy;

use Notify\Strategy\StrategyInterface;
use Notify\Message\MessageInterface;
use Notify\NotificationInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class TestStrategy implements StrategyInterface
{
    /**
     * @var MessageInterface[]
     */
    private $messages;

    /**
     * @var NotificationInterface
     */
    private $notification;

    /**
     * {@inheritDoc}
     */
    public function handle(array $messages, NotificationInterface $notification = null)
    {
        $this->messages = $messages;
        $this->notification = $notification;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getNotification()
    {
        return $this->notification;
    }
}
