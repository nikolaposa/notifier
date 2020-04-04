<?php

declare(strict_types=1);

namespace Notifier\Recipient;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Recipients implements IteratorAggregate
{
    /** @var Recipient[] */
    private $recipients;

    final public function __construct(Recipient ...$recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * @return Traversable|Recipient[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->recipients);
    }
}
