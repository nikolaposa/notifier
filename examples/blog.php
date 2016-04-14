<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Example;

use Notify\Contact\HasContactsInterface;
use Notify\Contact\Contacts;
use Notify\Contact\EmailContact;
use Notify\Message\Actor\ProvidesRecipientInterface;
use Notify\Message\Actor\Recipient;
use Notify\Message\Actor\EmptyRecipient;
use Notify\BaseNotification;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Content\TextContent;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;
use Notify\Strategy\SendStrategy;
use Notify\Message\Handler\TestHandler;

require_once __DIR__ . '/../vendor/autoload.php';

final class User implements HasContactsInterface, ProvidesRecipientInterface
{
    private $username;

    private $firstName;

    private $lastName;

    private $email;

    public function __construct(
        $username,
        $firstName,
        $lastName,
        $email
    ) {
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getContacts()
    {
        return new Contacts([
            new EmailContact($this->email),
        ]);
    }

    public function getMessageRecipient($messageType, $notificationId = null)
    {
        $name = sprintf('%s %s', $this->getFirstName(), $this->getLastName());

        $contacts = $this->getContacts();

        if ($messageType == EmailMessage::class) {
            if (false !== ($emailContact = $contacts->getOne(EmailContact::class))) {
                return new Recipient($name, $emailContact);
            }
        }

        return new EmptyRecipient();
    }
}

final class Post
{
    private $title;

    private $content;

    private $author;

    private $comments;

    public function __construct(
        $title,
        $content,
        User $author,
        array $comments = []
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->comments = $comments;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function comment(Comment $comment)
    {
        $this->comments[] = $comment;
    }
}

final class Comment
{
    private $authorName;

    private $authorEmail;

    private $content;

    public function __construct(
        $authorName,
        $authorEmail,
        $content
    ) {
        $this->authorName = $authorName;
        $this->authorEmail = $authorEmail;
        $this->content = $content;
    }

    public function getAuthorName()
    {
        return $this->authorName;
    }

    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    public function getContent()
    {
        return $this->content;
    }
}

final class NewCommentNotification extends BaseNotification
{
    const ID = 'new-comment';

    private $post;

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
                    $this->post->getAuthor()->getMessageRecipient(EmailMessage::class, self::ID)
                ]),
                'New comment',
                new TextContent(sprintf('%s left a new comment on your "%s" blog post', $this->comment->getAuthorName(), $this->post->getTitle())),
                new EmptySender(),
                new EmailOptions()
            ),
        ];
    }
}

$user = new User('admin', 'John', 'Doe', 'jd@example.com');
$post = new Post('Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $user);

$comment = new Comment('Jane', 'jane@example.com', 'Nice article!');
$post->comment($comment);

$defaultHandler = new TestHandler();
$defaultStrategy = new SendStrategy([
    EmailMessage::class => $defaultHandler,
]);
BaseNotification::setDefaultStrategy($defaultStrategy);

$newCommentNotification = new NewCommentNotification($post, $comment);
$newCommentNotification();

foreach ($defaultHandler->getMessages() as $message) {
    echo get_class($message) . ': ';
    echo $message->getContent()->get();
    echo "\n\n";
}
