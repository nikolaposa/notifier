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

    public function fakeMail(string $to, string $subject, string $message, string $additionalHeaders): bool
    {
        $this->sentEmail = [
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'additional_headers' => $additionalHeaders,
        ];

        return true;
    }

    /**
     * @test
     */
    public function it_sends_email_message(): void
    {
        $message = (new EmailMessage())
            ->from('noreply@test.com')
            ->to('john@example.com', 'John')
            ->to('jane@example.com', 'Jane')
            ->subject('Hey')
            ->body('Testing');

        $this->mailer->send($message);

        $this->assertNotEmpty($this->sentEmail);
        $this->assertSame('John <john@example.com>,Jane <jane@example.com>', $this->sentEmail['to']);
        $this->assertSame('Hey', $this->sentEmail['subject']);
        $this->assertSame('Testing', $this->sentEmail['message']);
        $this->assertSame("Content-type: text/plain; charset=utf-8\r\nFrom: noreply@test.com\r\n", $this->sentEmail['additional_headers']);
    }
}
