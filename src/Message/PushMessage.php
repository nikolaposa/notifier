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

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PushMessage extends AbstractMessage
{
    /**
     * @param Recipients $recipients
     * @param string|ContentProviderInterface|array $content
     * @param ActorInterface $sender
     */
    public function __construct(
        Recipients $recipients,
        $content
    ) {
        parent::__construct($recipients, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function getContent()
    {
        $content = $this->loadContent();

        if (is_array($content)) {
            return json_encode($content);
        }

        return $content;
    }

    /**
     * @return string
     */
    public function getRawContent()
    {
        $this->loadContent();

        return $this->loadContent();
    }
}
