<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\TestAsset\Message;

use Notify\Message\MessageInterface;
use Notify\Message\Actor\Recipients;
use Notify\Message\Content\ContentInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class DummyMessage implements MessageInterface
{
    /**
     * @var Recipients
     */
    private $recipients;

    /**
     * @var ContentInterface
     */
    private $content;

    public function __construct(Recipients $recipients, ContentInterface $content)
    {
        $this->recipients = $recipients;
        $this->content = $content;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function getContent()
    {
        return $this->content;
    }
}
