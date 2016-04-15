<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Options;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailOptions
{
    /**
     * @var string
     */
    private $contentType;

    /**
     * @var string
     */
    private $encoding;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $parameters;

    public function __construct(
        $contentType = 'text/plain',
        $encoding = 'utf-8',
        array $headers = [],
        array $parameters = []
    ) {
        $this->contentType = $contentType;
        $this->encoding = $encoding;
        $this->headers = $headers;
        $this->parameters = $parameters;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function isHtml()
    {
        return $this->getContentType() == 'text/html';
    }
}
