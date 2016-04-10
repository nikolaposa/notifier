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

use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class TemplatedContent implements ContentInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data;

    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * @var TemplateRendererInterface
     */
    private static $defaultTemplateRenderer;

    public function __construct($name, $data = [], TemplateRendererInterface $templateRenderer = null)
    {
        $this->name = $name;
        $this->data = $data;
        $this->templateRenderer = $templateRenderer;
    }

    public static function setDefaultTemplateRenderer(TemplateRendererInterface $defaultTemplateRenderer)
    {
        self::$defaultTemplateRenderer = $defaultTemplateRenderer;
    }

    private function getTemplateRenderer()
    {
        if (null === $this->templateRenderer) {
            if (null === self::$defaultTemplateRenderer) {
                throw new \RuntimeException('Template renderer not set');
            }

            $this->templateRenderer = self::$defaultTemplateRenderer;
        }

        return $this->templateRenderer;
    }

    public function get()
    {
        return $this->getTemplateRenderer()->render($this->name, $this->data);
    }
}
