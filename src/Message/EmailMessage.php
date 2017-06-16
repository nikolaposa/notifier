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

use Notify\Recipients;
use Notify\Message\Actor\ActorInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailMessage extends AbstractMessage
{
    /**
     * @var string
     */
    protected $subject;

    /**
     * @var ActorInterface
     */
    protected $from;

    /**
     * @var Options
     */
    protected $options;

    /**
     * @param Recipients $recipients
     * @param string $subject
     * @param string $content
     * @param ActorInterface $from
     * @param $options
     */
    public function __construct(
        Recipients $recipients,
        $subject,
        $content,
        ActorInterface $from = null,
        Options $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->subject = $subject;
        $this->from = $from;
        $this->options = $options;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function hasFrom()
    {
        return $this->from !== null;
    }
    
    public function getOptions()
    {
        if (null === $this->options) {
            $this->options = new Options([]);
        }

        return $this->options;
    }
}
