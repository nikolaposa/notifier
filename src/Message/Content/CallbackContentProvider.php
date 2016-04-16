<?php

namespace Notify\Message\Content;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class CallbackContentProvider implements ContentProviderInterface
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

    public function getContent()
    {
        return call_user_func($this->callback);
    }
}
