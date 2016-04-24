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
use Notify\Message\Options\OptionsInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PushMessage extends AbstractMessage implements HasOptionsInterface
{
    use HasOptionsTrait;

    /**
     * @param Recipients $recipients
     * @param string|ContentProviderInterface $content
     * @param OptionsInterface $options
     */
    public function __construct(
        Recipients $recipients,
        $content,
        OptionsInterface $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->options = $options;
    }
}
