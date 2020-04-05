<?php

declare(strict_types=1);

namespace Notifier\Tests\Channel;

use ErrorException;
use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Email\SimpleMailer;
use Notifier\Exception\SendingMessageFailed;
use PHPUnit\Framework\TestCase;

class SimpleMailerTest extends TestCase
{
    /** @var SimpleMailer */
    protected $mailer;

    /** @var array */
    protected $sentEmail;

    /** @var string|null */
    protected $error;

    protected function setUp(): void
    {
        $this->mailer = new SimpleMailer([$this, 'fakeMail']);
    }

    protected function tearDown(): void
    {
        $this->sentEmail = [];
        $this->error = null;
    }

    public function fakeMail(string $to, string $subject, string $message, string $headers): bool
    {
        if (null !== $this->error) {
            @ trigger_error($this->error);

            return false;
        }

        $this->sentEmail = [
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'headers' => $headers,
        ];

        return true;
    }

    /**
     * @test
     */
    public function it_sends_email_message(): void
    {
        $message = (new EmailMessage())
            ->from('testing@example.com')
            ->sender('admin@example.com')
            ->replyTo('admin@example.com')
            ->to('john@example.com', 'John')
            ->to('jane@example.com', 'Jane')
            ->cc('archive@example.com')
            ->bcc('check@example.com')
            ->subject('Hey')
            ->textBody('Testing');

        $this->mailer->send($message);

        $this->assertNotEmpty($this->sentEmail);
        $this->assertSame('John <john@example.com>, Jane <jane@example.com>', $this->sentEmail['to']);
        $this->assertSame('Hey', $this->sentEmail['subject']);
        $this->assertSame('Testing', $this->sentEmail['message']);
        $this->assertStringContainsString('From: testing@example.com', $this->sentEmail['headers']);
        $this->assertStringContainsString('Sender: admin@example.com', $this->sentEmail['headers']);
        $this->assertStringContainsString('Reply-To: admin@example.com', $this->sentEmail['headers']);
        $this->assertStringContainsString('To: John <john@example.com>, Jane <jane@example.com>', $this->sentEmail['headers']);
        $this->assertStringContainsString('Cc: archive@example.com', $this->sentEmail['headers']);
        $this->assertStringContainsString('Bcc: check@example.com', $this->sentEmail['headers']);
    }

    /**
     * @test
     */
    public function it_raises_exception_if_it_fails_to_send_message(): void
    {
        $message = (new EmailMessage())
            ->from('testing@example.com')
            ->to('invalid_email')
            ->subject('Test')
            ->textBody('Testing');
        $this->error = 'Failed to connect to mail server';

        try {
            $this->mailer->send($message);

            $this->fail('Exception should have been raised');
        } catch (SendingMessageFailed $exception) {
            $this->assertInstanceOf(ErrorException::class, $exception->getPrevious());
            $this->assertSame($this->error, $exception->getPrevious()->getMessage());
        }
    }
}
