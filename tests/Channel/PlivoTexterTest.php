<?php

declare(strict_types=1);

namespace Notifier\Tests\Channel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Notifier\Channel\Sms\PlivoTexter;
use Notifier\Channel\Sms\SmsMessage;
use Notifier\Exception\SendingMessageFailed;
use PHPUnit\Framework\TestCase;

class PlivoTexterTest extends TestCase
{
    /** @var PlivoTexter */
    protected $texter;

    /** @var MockHandler */
    protected $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        $this->texter = new PlivoTexter('auth_id', 'auth_token', new Client([
            'handler' => $this->mockHandler,
        ]));
    }

    /**
     * @test
     */
    public function it_sends_sms_message(): void
    {
        $this->mockHandler->append(new Response(200));

        $message = (new SmsMessage())
            ->from('1111')
            ->to('+123456')
            ->text('Hey');

        $this->texter->send($message);

        $request = $this->mockHandler->getLastRequest();
        $this->assertSame(json_encode([
            'src' => '1111',
            'dst' => '+123456',
            'text' => 'Hey',
        ]), (string) $request->getBody());
    }

    /**
     * @test
     */
    public function it_raises_exception_if_it_fails_to_send_message(): void
    {
        $this->mockHandler->append(new ServerException(
            'An error has occurred',
            $this->createMock(ServerRequest::class),
            new Response(500)
        ));

        $message = (new SmsMessage())
            ->from('1111')
            ->to('+123456')
            ->text('Hey');

        try {
            $this->texter->send($message);

            $this->fail('Exception should have been raised');
        } catch (SendingMessageFailed $exception) {
            $this->assertInstanceOf(GuzzleException::class, $exception->getPrevious());
        }
    }
}
