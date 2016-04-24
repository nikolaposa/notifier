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
use Notify\Message\Options\Options;

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

        if (null === $options) {
            $options = new Options([]);
        }

        $this->options = $options;
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }
}
