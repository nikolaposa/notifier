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

use Notify\Message\Actor\ActorInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
trait HasSenderTrait
{
    /**
     * @var ActorInterface
     */
    protected $sender;

    /**
     * @return ActorInterface
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return bool
     */
    public function hasSender()
    {
        return $this->sender !== null;
    }
}
