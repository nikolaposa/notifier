<?php

declare(strict_types=1);

namespace Notify\Message;

class PushMessage extends AbstractMessage
{
    use HasOptionsTrait;

    public function __construct(
        array $recipients,
        string $content,
        Options $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->options = $options;
    }
}
