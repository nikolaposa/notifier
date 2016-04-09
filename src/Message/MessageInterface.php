<?php

namespace Notify\Message;

use Notify\Message\Actor\Recipients;
use Notify\Message\Content\ContentInterface;

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
     * @return ContentInterface
     */
    public function getContent();
}
