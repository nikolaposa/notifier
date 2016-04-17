<?php

namespace Notify\Tests\TestAsset\Contact;

use Notify\Contact\AbstractContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TestContact extends AbstractContact
{
    protected function filter($value)
    {
        return trim($value);
    }
}
