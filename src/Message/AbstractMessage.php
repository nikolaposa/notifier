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

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractMessage
{
    /**
     * @var Recipients
     */
    protected $recipients;

    /**
     * @var string
     */
    protected $content;

    public function __construct(Recipients $recipients, string $content)
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
