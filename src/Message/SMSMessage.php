<?php

/*
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

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SMSMessage extends AbstractMessage implements HasSenderInterface
{
    const CONTENT_LENGTH_LIMIT = 160;

    use HasSenderTrait;

    /**
     * @param Recipients $recipients
     * @param string $subject
     * @param string|ContentProviderInterface $content
     * @param ActorInterface $sender
     * @param EmailOptions $options
     */
    public function __construct(
        Recipients $recipients,
        $content,
        ActorInterface $sender = null
    ) {
        parent::__construct($recipients, $content);

        $this->sender = $sender;
    }

    public function getContent()
    {
        $this->loadContent();

        return strlen($this->content) > self::CONTENT_LENGTH_LIMIT
            ? substr($this->content, 0, self::CONTENT_LENGTH_LIMIT)
            : $this->content;
    }

    public function getRawContent()
    {
        $this->loadContent();

        return $this->content;
    }
}
