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
     * @param int $maxColumnWidth
     */
    public function __construct($maxColumnWidth = 70)
    {
        $this->maxColumnWidth = (int) $maxColumnWidth;
    }

    /**
     * {@inheritDoc}
     */
    public function send(MessageInterface $message)
    {
        if (! $message instanceof EmailMessage) {
            throw UnsupportedMessageException::fromHandlerAndMessage($this, $message);
        }

        $this->doSend($message);
    }

    private function formatRecipients(array $recipients)
    {
        return array_map(function(RecipientInterface $recipient) {
            $to = $recipient->getContact()->getValue();

            if ($recipient->getName() !== '') {
                $to = $recipient->getName() . ' <' . $to . '>';
            }

            return $to;
        }, $recipients);
    }

    private function doSend(EmailMessage $message)
    {
        $recipients = $this->formatRecipients($message->getRecipients());

        $subject = $message->getSubject();

        $content = wordwrap($message->getContent()->getContent(), $this->maxColumnWidth);

        $options = $message->getOptions();

        $headers = ltrim(implode("\r\n", $options->getHeaders()) . "\r\n", "\r\n");
        $headers .= 'Content-type: ' . $options->getContentType() . '; charset=' . $options->getEncoding() . "\r\n";
        if ($options->getContentType() == 'text/html' && false === strpos($headers, 'MIME-Version:')) {
            $headers .= 'MIME-Version: 1.0' . "\r\n";
        }

        $parameters = implode(' ', $options->getParameters());

        mail($recipients, $subject, $content, $headers, $parameters);
    }
}
