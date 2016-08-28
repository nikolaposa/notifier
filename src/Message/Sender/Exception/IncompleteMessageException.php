<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Sender\Exception;

use LogicException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class IncompleteMessageException extends LogicException implements ExceptionInterface
{
}