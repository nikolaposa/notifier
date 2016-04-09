<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Example\Notification;

use Notify\BaseNotification;
use Notify\Example\Entity\Post;
use Notify\Example\Entity\Comment;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Content\TextContent;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class NewCommentNotification extends BaseNotification
{
    const ID = 'new-comment';

    /**
     * @var Post
     */
    private $post;

    /**
     * @var Comment
     */
    private $comment;

    public function __construct(Post $post, Comment $comment)
    {
        $this->post = $post;
        $this->comment = $comment;
    }

    public function getName()
    {
        return 'New comment notification';
    }

    protected function getMessages()
    {
        return [
            new EmailMessage(
                new Recipients([
                    $this->post->getAuthor()->getMessageRecipient(self::ID, EmailMessage::class)
                ]),
                'New comment',
                new TextContent(sprintf('%s left a new comment on your "%s" blog post', $this->comment->getAuthorName(), $this->post->getTitle())),
                new EmptySender(),
                new EmailOptions()
            ),
        ];
    }
}
