<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Strategy;

use Notify\Message\Handler\HandlerInterface;
use Notify\Message\MessageInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class SendStrategy implements StrategyInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(array $messages)
    {
        foreach ($messages as $message) {
            /* @var $message MessageInterface */

            $messageType = get_class($message);

            if (!isset($this->handlers[$messageType])) {
                continue;
            }

            $this->handlers[$messageType]->send($message);
        }
    }
}
