<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Actor;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface ProvidesRecipientInterface
{
    /**
     * @param string $messageType
     * @param string $notificationId OPTIONAL
     * @return ActorInterface
     */
    public function getMessageRecipient($messageType, $notificationId = null);
}
