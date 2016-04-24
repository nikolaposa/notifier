<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Contact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PhoneContact extends AbstractContact
{
    protected function filter($phone)
    {
        return preg_replace('/[^\+0-9\x]/', '', $phone);
    }
}
