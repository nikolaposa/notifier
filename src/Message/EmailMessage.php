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
use Notify\Message\Content\ContentProviderInterface;
use Notify\Message\Actor\SenderInterface;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class EmailMessage extends AbstractMessage implements
    HasSubjectInterface,
    HasSenderInterface
{
    /**
     * @var string
     */
    private $subject;

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
     * @param string|ContentProviderInterface $content
     * @param SenderInterface $sender
     * @param EmailOptions $options
     */
    public function __construct(
        Recipients $recipients,
        $subject,
        $content,
        SenderInterface $sender,
        EmailOptions $options
    ) {
        parent::__construct($recipients, $content);
        $this->subject = $subject;
        $this->sender = $sender;
        $this->options = $options;
    }

    public function getSubject()
    {
        return $this->subject;
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
