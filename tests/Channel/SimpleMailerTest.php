<?php

declare(strict_types=1);

namespace Notifier\Tests\Channel;

use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Email\SimpleMailer;
use PHPUnit\Framework\TestCase;

class SimpleMailerTest extends TestCase
{
    /** @var SimpleMailer */
    protected $mailer;

    /** @var array */
    protected $sentEmail;

    protected function setUp(): void
    {
        $this->mailer = new SimpleMailer([$this, 'fakeMail']);
    }

    protected function tearDown(): void
    {
        $this->sentEmail = [];
    }

    public function fakeMail(string $to, string $subject, string $message, string $headers): bool
    {
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
}
