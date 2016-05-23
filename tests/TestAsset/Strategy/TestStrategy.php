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
use Notify\NotificationInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class TestStrategy implements StrategyInterface
{
    /**
     * @var NotificationInterface
     */
    private $notification;

    public function handle(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function getMessages()
    {
        return $this->getNotification()->getMessages();
    }
}
