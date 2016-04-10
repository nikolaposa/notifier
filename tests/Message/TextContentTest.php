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
use Notify\Message\Content\TextContent;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TextContentTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingContent()
    {
        $content = new TextContent('test test test');

        $this->assertEquals('test test test', $content->get());
    }
}
