<?php

declare(strict_types=1);

namespace Notify\Message\Actor;

class Actor implements ActorInterface
{
    /**
     * @var string
     */
    private $contact;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $contact, string $name = null)
    {
        $this->contact = $contact;
        $this->name = $name;
    }

    public function getContact() : string
    {
        return $this->contact;
    }

    public function getName()
    {
        return $this->name;
    }
}
