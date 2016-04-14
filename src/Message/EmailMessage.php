<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message;

use Notify\Message\Actor\Recipients;
use Notify\Message\Content\ContentInterface;
use Notify\Message\Actor\SenderInterface;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class EmailMessage implements
    MessageInterface,
    HasSubjectInterface,
    HasSenderInterface
{
    /**
     * @var Recipients
     */
    private $recipients;

    /**
     *
     * @var string
     */
    private $subject;

    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var SenderInterface
     */
    private $sender;

    /**
     * @var EmailOptions
     */
    private $options;

    /**
     * @param Recipients $recipients
     * @param string $subject
     * @param ContentInterface $content
     * @param SenderInterface $sender
     * @param EmailOptions $options
     */
    public function __construct(
        Recipients $recipients,
        $subject,
        ContentInterface $content,
        SenderInterface $sender,
        EmailOptions $options
    ) {
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->content = $content;
        $this->sender = $sender;
        $this->options = $options;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
