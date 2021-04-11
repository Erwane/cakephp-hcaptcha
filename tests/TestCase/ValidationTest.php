<?php
declare(strict_types=1);

namespace HCaptcha\Test\TestCase;

use Cake\Http\Client;
use Cake\TestSuite\TestCase;
use HCaptcha\Validation;

/**
 * Class ValidationTest
 *
 * @package HCaptcha\Test\TestCase
 * @uses \HCaptcha\Validation
 * @coversDefaultClass \HCaptcha\Validation
 */
class ValidationTest extends TestCase
{
    /**
     * @test
     * @covers ::getClient
     */
    public function testGetClient(): void
    {
        $client = Validation::getClient();
        self::assertInstanceOf(Client::class, $client);
        self::assertSame(3, $client->getConfig('timeout'));
    }

    /**
     * @test
     * @covers ::setClient
     * @covers ::hcaptcha
     */
    public function testHcaptchaResponseFail(): void
    {
        $client = $this->createPartialMock(Client::class, ['post']);

        $response = $this->createPartialMock(Client\Response::class, ['isSuccess']);

        $client->expects(self::once())
            ->method('post')
            ->with('https://hcaptcha.com/siteverify', [
                'secret' => 'hcaptcha-secret',
                'response' => 'testing-post-fail',
            ])
            ->willReturn($response);
        $response->expects(self::once())->method('isSuccess')->willReturn(false);

        Validation::setClient($client);
        $result = Validation::hcaptcha('testing-post-fail');
        self::assertFalse($result);
    }

    /**
     * @test
     * @covers ::setClient
     * @covers ::hcaptcha
     */
    public function testHcaptchaSuccessNotSet(): void
    {
        $client = $this->createPartialMock(Client::class, ['post']);
        $response = $this->createPartialMock(Client\Response::class, ['isSuccess', 'getJson']);

        $client->expects(self::exactly(2))
            ->method('post')
            ->willReturn($response);

        $response->expects(self::exactly(2))
            ->method('isSuccess')
            ->willReturn(true);

        $response->expects(self::exactly(2))
            ->method('getJson')
            ->willReturnOnConsecutiveCalls(false, ['response']);

        Validation::setClient($client);

        $result = Validation::hcaptcha('testing-success');
        self::assertFalse($result);

        $result = Validation::hcaptcha('testing-success');
        self::assertFalse($result);
    }

    /**
     * @test
     * @covers ::setClient
     * @covers ::hcaptcha
     */
    public function testHcaptchaSuccess(): void
    {
        $client = $this->createPartialMock(Client::class, ['post']);
        $response = $this->createPartialMock(Client\Response::class, ['isSuccess', 'getJson']);

        $client->expects(self::once())
            ->method('post')
            ->willReturn($response);

        $response->expects(self::once())
            ->method('isSuccess')
            ->willReturn(true);

        $response->expects(self::once())
            ->method('getJson')
            ->willReturn(['success' => true]);

        Validation::setClient($client);

        $result = Validation::hcaptcha('testing-success');
        self::assertTrue($result);
    }
}
