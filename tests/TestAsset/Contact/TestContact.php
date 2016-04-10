<?php

namespace Notify\Tests\TestAsset\Contact;

use Notify\Contact\BaseContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class TestContact extends BaseContact
{
    protected function filter($value)
    {
        return trim($value);
    }
}
