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

use Notify\Recipients;
use Notify\Message\Content\ContentProviderInterface;
use Notify\Message\Actor\ActorInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SMSMessage extends AbstractMessage
{
    const CONTENT_LENGTH_LIMIT = 160;

    /**
     * @var ActorInterface
     */
    protected $sender;

    /**
     * @param Recipients $recipients
     * @param string|ContentProviderInterface $content
     * @param ActorInterface $sender
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
        $content = $this->content;

        return strlen($content) > self::CONTENT_LENGTH_LIMIT
            ? substr($content, 0, self::CONTENT_LENGTH_LIMIT)
            : $content;
    }

    public function getRawContent()
    {
        return $this->content;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function hasSender()
    {
        return $this->sender !== null;
    }
}
