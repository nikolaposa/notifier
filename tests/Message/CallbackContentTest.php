<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Message;

use PHPUnit_Framework_TestCase;
use Notify\Message\Content\CallbackContent;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class CallbackContentTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingContentViaCallback()
    {
        $template = 'Hello %s';
        $data = ['test'];
        $renderer = function () use ($template, $data) {
            return vsprintf($template, $data);
        };

        $content = new CallbackContent($renderer);

        $this->assertEquals('Hello test', $content->get());
    }
}
