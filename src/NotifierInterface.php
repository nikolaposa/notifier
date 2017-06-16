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
interface NotifierInterface
{
    /**
     * @param array $notificationRecipients
     * @param NotificationInterface $notification
     *
     * @return void
     */
    public function notify(array $notificationRecipients, NotificationInterface $notification);
}
