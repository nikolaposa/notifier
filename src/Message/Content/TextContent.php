<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Content;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class TextContent implements ContentInterface
{
    /**
     * @var string
     */
    private $content;

    public function __construct($content)
    {
        $this->content = (string) $content;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent()
    {
        return $this->content;
    }
}
