<?php

namespace Notify\Message;

use Notify\Message\Actor\Recipients;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface MessageInterface
{
    /**
     * @return Recipients
     */
    public function getRecipients();

    /**
     * @return string
     */
    public function getContent();
}
