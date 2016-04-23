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
use Notify\Message\Actor\ActorInterface;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailMessage extends AbstractMessage implements
    HasSubjectInterface,
    HasSenderInterface
{
    use HasSenderTrait;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var EmailOptions
     */
    private $options;

    /**
     * @param Recipients $recipients
     * @param string $subject
     * @param string|ContentProviderInterface $content
     * @param ActorInterface $sender
     * @param EmailOptions $options
     */
    public function __construct(
        Recipients $recipients,
        $subject,
        $content,
        ActorInterface $sender = null,
        EmailOptions $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->subject = $subject;

        $this->sender = $sender;

        if (null === $options) {
            $options = new EmailOptions();
        }
        $this->options = $options;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
