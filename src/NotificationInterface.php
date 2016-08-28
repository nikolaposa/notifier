<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface NotificationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getMessages();
}
