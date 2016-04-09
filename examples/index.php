<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Notify\Example\Entity\Post;
use Notify\Example\Entity\User;
use Notify\Example\Entity\Comment;
use Notify\Example\Notification\NewCommentNotification;
use Notify\Strategy\SendStrategy;
use Notify\Message\Handler\TestHandler;

$user = new User('admin', 'John', 'Doe', 'jd@example.com');
$post = new Post('Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $user);

$comment = new Comment('Jane', 'jane@example.com', 'Nice article!');
$post->comment($comment);

$newCommentNotification = new NewCommentNotification($post, $comment);
$newCommentNotification(new SendStrategy([
    Notify\Message\EmailMessage::class => new TestHandler(),
]));
