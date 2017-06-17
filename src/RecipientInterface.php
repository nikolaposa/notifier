<?php

declare(strict_types=1);

namespace Notify;

interface RecipientInterface
{
    /**
     * @param NotificationDerivative $notificationDerivative
     *
     * @return string
     */
    public function getRecipientContact(NotificationDerivative $notificationDerivative) : string;

    /**
     * @return string
     */
    public function getRecipientName() : string;

    /**
     * @param NotificationDerivative $notificationDerivative
     *
     * @return bool
     */
    public function shouldBeNotified(NotificationDerivative $notificationDerivative) : bool;

    /**
     * @param NotificationDerivative $notificationDerivative
     *
     * @return void
     */
    public function onNotified(NotificationDerivative $notificationDerivative);
}
