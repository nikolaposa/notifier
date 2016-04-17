<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

use Notify\Strategy\StrategyInterface;
use Notify\Exception\NotificationStrategyNotSuppliedException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractNotification implements NotificationInterface
{
    /**
     * @var StrategyInterface
     */
    private static $defaultStrategy = null;

    /**
     * @param StrategyInterface $defaultStrategy
     */
    public static function setDefaultStrategy(StrategyInterface $defaultStrategy)
    {
        self::$defaultStrategy = $defaultStrategy;
    }

    public static function resetDefaultStrategy()
    {
        self::$defaultStrategy = null;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(StrategyInterface $strategy = null)
    {
        if (null === $strategy) {
            if (null === self::$defaultStrategy) {
                throw NotificationStrategyNotSuppliedException::forNotification($this);
            }

            $strategy = self::$defaultStrategy;
        }

        $strategy->handle($this);
    }
}
