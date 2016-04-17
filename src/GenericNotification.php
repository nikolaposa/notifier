<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

use Notify\Message\MessageInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class GenericNotification extends AbstractNotification
{
    /**
     * @var MessageInterface[]
     */
    private $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Generic';
    }

    /**
     * {@inheritDoc}
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
