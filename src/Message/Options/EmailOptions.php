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
 * @deprecated since version v2.1.0
 * @codeCoverageIgnore
 */
class EmailOptions
{
    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $parameters;

    public function __construct(
        $contentType = 'text/plain',
        $encoding = 'utf-8',
        array $headers = [],
        array $parameters = []
    ) {
        trigger_error(
            __CLASS__ . ' is deprecated as of Notify 2.1.0; use ' . Options::class . ' instead',
            E_USER_DEPRECATED
        );

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

    public function isHtml()
    {
        return $this->getContentType() == 'text/html';
    }
}
