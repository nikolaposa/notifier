<?php

declare(strict_types=1);

namespace Notify\Message\Actor;

interface ActorInterface
{
    /**
     * @return string
     */
    public function getContact() : string;

    /**
     * @return string|null
     */
    public function getName();
}
