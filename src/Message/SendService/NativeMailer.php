<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\SendService;

use Notify\Message\MessageInterface;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\ActorInterface;
use Notify\Message\SendService\Exception\UnsupportedMessageException;
use Notify\Message\SendService\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class NativeMailer implements SendServiceInterface
{
    /**
     * @var int
     */
    private $maxColumnWidth;

    /**
     * @var callable
     */
    private $mailer = 'mail';

    /**
     * @param int $maxColumnWidth
     */
    public function __construct($maxColumnWidth = 70, callable $mailer = null)
    {
        $this->maxColumnWidth = (int) $maxColumnWidth;

        if (null !== $mailer) {
            $this->mailer = $mailer;
        }
    }

    public function send(MessageInterface $message)
    {
        if (!$message instanceof EmailMessage) {
            throw UnsupportedMessageException::fromSendServiceAndMessage($this, $message);
        }

        $result = call_user_func(
            $this->mailer,
            $this->getRecipients($message),
            $this->getSubject($message),
            $this->getContent($message),
            $this->getHeaders($message),
            $this->getParameters($message)
        );

        if (false === $result) {
            throw new RuntimeException('Email has not been accepted for delivery');
        }
    }

    private function getRecipients(EmailMessage $message)
    {
        $recipientsString = array_map(function (ActorInterface $recipient) {
            $to = $recipient->getContact()->getValue();

            if ($recipient->getName() !== '') {
                $to = $recipient->getName() . ' <' . $to . '>';
            }

            return $to;
        }, $message->getRecipients()->toArray());

        $recipientsString = implode(',', $recipientsString);

        return $recipientsString;
    }

    private function getSubject(EmailMessage $message)
    {
        return $message->getSubject();
    }

    private function getContent(EmailMessage $message)
    {
        return wordwrap($message->getContent(), $this->maxColumnWidth);
    }

    private function getHeaders(EmailMessage $message)
    {
        $options = $message->getOptions();

        $headers = ltrim(implode("\r\n", $options->get('headers', [])) . "\r\n", "\r\n");

        $contentType = $options->get('content_type', 'text/plain');

        $headers .= 'Content-type: ' . $contentType . '; charset=' . $options->get('encoding', 'utf-8') . "\r\n";

        if ($contentType == 'text/html' && false === strpos($headers, 'MIME-Version:')) {
            $headers .= "MIME-Version: 1.0\r\n";
        }

        if ($message->hasSender()) {
            $sender = $message->getSender();

            $headers .= 'From: ' . $sender->getContact()->getValue() . "\r\n" .
                'Reply-To: ' . $sender->getContact()->getValue() . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        }

        return $headers;
    }

    private function getParameters(EmailMessage $message)
    {
        return implode(' ', $message->getOptions()->get('parameters', []));
    }
}
