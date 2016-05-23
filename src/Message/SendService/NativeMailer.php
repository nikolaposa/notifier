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

        $this->doSend($message);
    }

    private function formatRecipients(Recipients $recipients)
    {
        $recipientsString = array_map(function (ActorInterface $recipient) {
            $to = $recipient->getContact()->getValue();

            if ($recipient->getName() !== '') {
                $to = $recipient->getName() . ' <' . $to . '>';
            }

            return $to;
        }, $recipients->toArray());

        $recipientsString = implode(',', $recipientsString);

        return $recipientsString;
    }

    private function buildHeaders(EmailMessage $message)
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

    private function doSend(EmailMessage $message)
    {
        $recipients = $this->formatRecipients($message->getRecipients());

        $subject = $message->getSubject();

        $content = wordwrap($message->getContent(), $this->maxColumnWidth);

        $headers = $this->buildHeaders($message);

        $parameters = implode(' ', $message->getOptions()->get('parameters', []));

        $result = call_user_func($this->mailer, $recipients, $subject, $content, $headers, $parameters);

        if (false === $result) {
            throw new RuntimeException('Email has not been accepted for delivery');
        }
    }
}
