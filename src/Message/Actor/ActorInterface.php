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

use Notify\Contact\ContactInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface ActorInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ContactInterface
     */
    public function getContact();
}
