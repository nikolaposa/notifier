<?php

namespace Notify\Message\Content;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class CallbackContent implements ContentInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function get()
    {
        return call_user_func($this->callback);
    }
}
