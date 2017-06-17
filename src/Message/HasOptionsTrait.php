<?php

declare(strict_types=1);

namespace Notify\Message;

trait HasOptionsTrait
{
    /**
     * @var Options
     */
    protected $options;

    public function getOptions() : Options
    {
        if (null === $this->options) {
            $this->options = new Options([]);
        }

        return $this->options;
    }
}
