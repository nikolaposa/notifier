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

use Notify\Message\SendService\SendServiceInterface;
use Notify\Message\MessageInterface;
use Notify\NotificationInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class SendStrategy extends AbstractStrategy implements LoggerAwareInterface
{
    const CATCH_ALL_HANDLER = '*';

    use LoggerAwareTrait;

    /**
     * @var SendServiceInterface[]
     */
    private $sendServices;

    public function __construct(array $sendServices)
    {
        $this->sendServices = $sendServices;

        $this->setLogger(new NullLogger());
    }

    protected function doHandle(array $messages, NotificationInterface $notification)
    {
        foreach ($messages as $message) {
            /* @var $message MessageInterface */

            $messageType = get_class($message);

            if (!isset($this->sendServices[$messageType])) {
                $this->logger->log(LogLevel::NOTICE, 'unsupported message type: {messageType}', [
                    'messageType' => $messageType,
                ]);

                continue;
            }

            try {
                $this->sendServices[$messageType]->send($message);
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
