<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Model;

class Todo
{
    /** @var string */
    protected $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
