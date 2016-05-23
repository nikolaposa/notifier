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
use Notify\Message\Actor\ActorInterface;
use Notify\Message\SendService\Exception\UnsupportedMessageException;
use Notify\Message\SendService\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class NativeMailer implements SendServiceInterface
{
    const DEFAULT_MAX_COLUMN_WIDTH = 70;

    /**
     * @var int
     */
    private $maxColumnWidth;

    /**
     * @var callable
     */
    private $mailer = 'mail';

    /**
     * @var EmailMessage
     */
    private $message;

    /**
     * @var array
     */
    private $mailParts = [];

    /**
     * @param int $maxColumnWidth
     */
    public function __construct($maxColumnWidth = self::DEFAULT_MAX_COLUMN_WIDTH, callable $mailer = null)
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

        $this->message = $message;

        $this->buildMailParts();

        $sendResult = $this->doSend();

        if (false === $sendResult) {
            throw new RuntimeException('Email has not been accepted for delivery');
        }
    }

    private function buildMailParts()
    {
        $this->buildMailTo();
        $this->buildMailSubject();
        $this->buildMailMessage();
        $this->buildMailHeaders();
        $this->buildMailParameters();
    }

    private function buildMailTo()
    {
        $recipientsString = array_map(function (ActorInterface $recipient) {
            $to = $recipient->getContact()->getValue();

            if ($recipient->getName() !== '') {
                $to = $recipient->getName() . ' <' . $to . '>';
            }

            return $to;
        }, $this->message->getRecipients()->toArray());

        $this->mailParts['to'] = implode(',', $recipientsString);
    }

    private function buildMailSubject()
    {
        $this->mailParts['subject'] = $this->message->getSubject();
    }

    private function buildMailMessage()
    {
        $this->mailParts['message'] = wordwrap($this->message->getContent(), $this->maxColumnWidth);
    }

    private function buildMailHeaders()
    {
        $options = $this->message->getOptions();

        $headers = ltrim(implode("\r\n", $options->get('headers', [])) . "\r\n", "\r\n");

        $contentType = $options->get('content_type', 'text/plain');

        $headers .= 'Content-type: ' . $contentType . '; charset=' . $options->get('encoding', 'utf-8') . "\r\n";

        if ($contentType == 'text/html' && false === strpos($headers, 'MIME-Version:')) {
            $headers .= "MIME-Version: 1.0\r\n";
        }

        if ($this->message->hasSender()) {
            $sender = $this->message->getSender();

            $headers .= 'From: ' . $sender->getContact()->getValue() . "\r\n" .
                'Reply-To: ' . $sender->getContact()->getValue() . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        }

        $this->mailParts['headers'] = $headers;
    }

    private function buildMailParameters()
    {
        $this->mailParts['parameters'] = implode(' ', $this->message->getOptions()->get('parameters', []));
    }

    private function doSend()
    {
        return call_user_func(
            $this->mailer,
            $this->mailParts['to'],
            $this->mailParts['subject'],
            $this->mailParts['message'],
            $this->mailParts['headers'],
            $this->mailParts['parameters']
        );
    }
}
