<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Actor;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Recipient extends GenericActor implements RecipientInterface
{
    public function __toString()
    {
        if (null !== ($name = $this->getName())) {
            return $name . ' <' . $this->getContact()->getValue() . '>';
        }

        return $this->getContact()->getValue();
    }
}
