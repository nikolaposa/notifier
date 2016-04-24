<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message;

use Notify\Message\Actor\Recipients;
use Notify\Message\Content\ContentProviderInterface;
use Notify\Message\Actor\ActorInterface;
use Notify\Message\Options\Options;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailMessage extends AbstractMessage implements
    HasSubjectInterface,
    HasSenderInterface,
    HasOptionsInterface
{
    use HasSenderTrait;
    use HasOptionsTrait;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @param Recipients $recipients
     * @param string $subject
     * @param string|ContentProviderInterface $content
     * @param ActorInterface $sender
     * @param $options
     */
    public function __construct(
        Recipients $recipients,
        $subject,
        $content,
        ActorInterface $sender = null,
        $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->subject = $subject;
        $this->sender = $sender;

        $options = $this->handleDeprecatedOptions($options);

        $this->options = $options;
    }

    private function handleDeprecatedOptions($options)
    {
        if (null !== $options && $options instanceof EmailOptions) {
            $options = new Options([
                'content_type' => $options->getContentType(),
                'encoding' => $options->getEncoding(),
                'headers' => $options->getHeaders(),
                'parameters' => $options->getParameters(),
                'html' => $options->getContentType() == 'text/html',
            ]);
        }

        return $options;
    }

    public function getSubject()
    {
        return $this->subject;
    }
}
