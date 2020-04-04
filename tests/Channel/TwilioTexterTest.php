<?php

declare(strict_types=1);

namespace Notifier\Tests\Channel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Notifier\Channel\Sms\SmsMessage;
use Notifier\Channel\Sms\TwilioTexter;
use Notifier\Exception\SendingMessageFailed;
use PHPUnit\Framework\TestCase;

class TwilioTexterTest extends TestCase
{
    /** @var TwilioTexter */
    protected $texter;

    /** @var MockHandler */
    protected $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        $this->texter = new TwilioTexter('ACXXX', 'abcd1234', new Client([
            'handler' => $this->mockHandler,
        ]));
    }

    /**
     * @test
     */
    public function it_sends_sms_message(): void
    {
        $this->mockHandler->append(new Response(200, [], (string) json_encode([
            'account_sid' => 'ACXXX',
            'api_version' => '2010-04-01',
            'status' => 'sent',
        ])));

        $message = (new SmsMessage())
            ->from('1111')
            ->to('+123456')
            ->text('Hey');

        $this->texter->send($message);

        $request = $this->mockHandler->getLastRequest();
        $this->assertSame(http_build_query([
            'Body' => 'Hey',
            'From' => '1111',
            'To' => '+123456',
        ]), (string) $request->getBody());
    }

    /**
     * @test
     */
    public function it_raises_exception_if_sending_message_fails(): void
    {
        $this->mockHandler->append(new ServerException('An error has occurred', $this->createMock(ServerRequest::class)));

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
