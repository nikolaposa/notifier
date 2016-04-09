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
use Notify\Message\Content\ContentInterface as Content;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class SmsMessage implements MessageInterface
{
    /**
     * @var Recipients
     */
    private $recipients;

    /**
     * @var Content
     */
    private $content;

    public function __construct(Recipients $recipients, Content $content)
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
