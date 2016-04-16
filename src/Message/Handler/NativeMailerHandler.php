<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Handler;

use Notify\Message\MessageInterface;
use Notify\Message\EmailMessage;
use Notify\Exception\UnsupportedMessageException;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\RecipientInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class NativeMailerHandler implements HandlerInterface
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

    /**
     * {@inheritDoc}
     */
    public function send(MessageInterface $message)
    {
        if (!$message instanceof EmailMessage) {
            throw UnsupportedMessageException::fromHandlerAndMessage($this, $message);
        }

        $this->doSend($message);
    }

    private function formatRecipients(Recipients $recipients)
    {
        $recipientsString = array_map(function (RecipientInterface $recipient) {
            $to = $recipient->getContact()->getValue();

            if ($recipient->getName() !== '') {
                $to = $recipient->getName() . ' <' . $to . '>';
            }

            return $to;
        }, $recipients->toArray());

        $recipientsString = implode(',', $recipientsString);

        return $recipientsString;
    }

    private function doSend(EmailMessage $message)
    {
        $recipients = $this->formatRecipients($message->getRecipients());

        $subject = $message->getSubject();

        $content = wordwrap($message->getContent(), $this->maxColumnWidth);

        $options = $message->getOptions();

        $headers = ltrim(implode("\r\n", $options->getHeaders()) . "\r\n", "\r\n");
        $headers .= 'Content-type: ' . $options->getContentType() . '; charset=' . $options->getEncoding() . "\r\n";
        if ($options->getContentType() == 'text/html' && false === strpos($headers, 'MIME-Version:')) {
            $headers .= "MIME-Version: 1.0\r\n";
        }

        $parameters = implode(' ', $options->getParameters());

        call_user_func($this->mailer, $recipients, $subject, $content, $headers, $parameters);
    }
}
