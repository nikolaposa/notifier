<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Contact;

use Notify\Exception\InvalidContactException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class EmailContact extends BaseContact
{
    protected function filter($email)
    {
        $email = trim($email);
        
        if (false === filter_var($email, FILTER_SANITIZE_EMAIL)) {
            throw InvalidContactException::fromInvalidValue($email, 'email address');
        }

        return $email;
    }
}
