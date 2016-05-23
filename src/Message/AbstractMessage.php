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
use Notify\Exception\InvalidArgumentException;
use JsonSerializable;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractMessage implements MessageInterface, JsonSerializable
{
    /**
     * @var Recipients
     */
    protected $recipients;

    /**
     *
     * @var string|ContentProviderInterface
     */
    protected $content;

    public function __construct(Recipients $recipients, $content)
    {
        $this->recipients = $recipients;

        if (!(is_string($content) || $content instanceof ContentProviderInterface)) {
            throw new InvalidArgumentException(sprintf(
                'Message content should be either string or %s instance',
                ContentProviderInterface::class
            ));
        }

        $this->content = $content;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function getContent()
    {
        return $this->loadContent();
    }

    /**
     * @return string
     */
    protected function loadContent()
    {
        if ($this->content instanceof ContentProviderInterface) {
            $this->content = $this->content->getContent();
        }

        return $this->content;
    }

    public function jsonSerialize()
    {
        return [
            'recipients' => $this->getRecipients(),
        ];
    }
}
