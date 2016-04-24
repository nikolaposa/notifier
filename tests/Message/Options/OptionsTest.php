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
use Notify\Message\Options\Options;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class OptionsTest extends PHPUnit_Framework_TestCase
{
    public function testCheckingWhetherOptionExists()
    {
        $options = new Options([
            'foo' => 'bar',
        ]);

        $this->assertTrue($options->has('foo'));
        $this->assertFalse($options->has('baz'));
    }

    public function testGettingOption()
    {
        $options = new Options([
            'foo' => 'bar',
        ]);

        $this->assertEquals('bar', $options->get('foo'));
    }

    public function testGettingOptionWhichDoesNotExistReturnsDefaultValue()
    {
        $options = new Options([
            'foo' => 'bar',
        ]);

        $this->assertEquals('default', $options->get('baz', 'default'));
    }

    public function testOptionsToArray()
    {
        $options = new Options([
            'opt1' => 'val1',
            'opt2' => 'val2',
            'opt3' => 'val3',
        ]);

        $allOptions = $options->toArray();

        $this->assertInternalType('array', $allOptions);
        $this->assertCount(3, $allOptions);
        $this->assertArrayHasKey('opt1', $allOptions);
        $this->assertEquals('val1', $allOptions['opt1']);
    }
}
