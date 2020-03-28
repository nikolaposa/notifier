<?php

declare(strict_types=1);

namespace Notifier\Channel\Push;

class PushMessage
{
    /** @var string */
    public $to;

    /** @var string */
    public $title = '';

    /** @var string */
    public $message = '';

    /** @var array */
    public $devices = [];

    public function to(string $id)
    {
        $this->to = $id;
        return $this;
    }

    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function message(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function devices(array $devices)
    {
        $this->devices = $devices;
        return $this;
    }
}
