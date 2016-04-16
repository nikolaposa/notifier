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
use Notify\NotificationInterface;
use Notify\Exception\MessageSendFailedException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class SendStrategy implements StrategyInterface, LoggerAwareInterface
{
    const CATCH_ALL_HANDLER = '*';

    use LoggerAwareTrait;

    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;

        $this->setLogger(new NullLogger());
    }

    /**
     * {@inheritDoc}
     */
    public function handle(array $messages, NotificationInterface $notification = null)
    {
        foreach ($messages as $message) {
            /* @var $message MessageInterface */

            $messageType = get_class($message);

            if (!isset($this->handlers[$messageType])) {
                $this->logger->log(LogLevel::NOTICE, 'unsupported message type: {messageType}', [
                    'messageType' => $messageType,
                ]);

                continue;
            }

            try {
                $this->handlers[$messageType]->send($message);
            } catch (\Exception $ex) {
                $this->logger->log(LogLevel::ERROR, 'message send failure', [
                    'notification' => $notification->getName(),
                    'message' => $message,
                    'exception' => $ex,
                ]);

                continue;
            }
        }
    }
}
