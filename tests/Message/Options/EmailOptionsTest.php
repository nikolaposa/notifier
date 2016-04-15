<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Message\Options;

use PHPUnit_Framework_TestCase;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailOptionsTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultEmailOptions()
    {
        $options = new EmailOptions();

        $this->assertEquals('text/plain', $options->getContentType());
        $this->assertEquals('utf-8', $options->getEncoding());
        $this->assertEmpty($options->getHeaders());
        $this->assertEmpty($options->getParameters());
    }

    public function testCustomEmailOptions()
    {
        $options = new EmailOptions(
            'text/html',
            'utf-8',
            ['MIME-Version: 1.0'],
            ['-fwebmaster@example.com']
        );

        $this->assertEquals('text/html', $options->getContentType());
        $this->assertEquals('utf-8', $options->getEncoding());
        $this->assertEquals(['MIME-Version: 1.0'], $options->getHeaders());
        $this->assertEquals(['-fwebmaster@example.com'], $options->getParameters());
    }

    public function testIsHtmlHelperMethod()
    {
        $options = new EmailOptions('text/html');

        $this->assertTrue($options->isHtml());
    }
}
