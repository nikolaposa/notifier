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

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PushMessage extends AbstractMessage
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * @param Recipients $recipients
     * @param string|ContentProviderInterface $content
     * @param Options $options
     */
    public function __construct(
        Recipients $recipients,
        $content,
        Options $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->options = $options;
    }

    public function getOptions()
    {
        if (null === $this->options) {
            $this->options = new Options([]);
        }

        return $this->options;
    }
}
