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
final class Notifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function notify(array $notificationRecipients, NotificationInterface $notification)
    {
        $this->notifyIndividually($notificationRecipients, $notification);
    }
}
