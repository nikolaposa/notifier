<?php

declare(strict_types=1);

namespace Notify;

use ArrayIterator;
use IteratorAggregate;

class Recipients implements IteratorAggregate
{
    /**
     * @var RecipientInterface[]
     */
    private $recipients;

    public function __construct(RecipientInterface ...$recipients)
    {
        $this->recipients = $recipients;
    }

    public static function fromArray(array $recipients)
    {
        return new static(...$recipients);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->recipients);
    }
}
