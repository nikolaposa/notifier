<?php

declare(strict_types=1);

namespace Notify\Message;

use Notify\Recipients;

class PushMessage extends AbstractMessage
{
    use HasOptionsTrait;

    public function __construct(
        Recipients $recipients,
        string $content,
        Options $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->options = $options;
    }
}
