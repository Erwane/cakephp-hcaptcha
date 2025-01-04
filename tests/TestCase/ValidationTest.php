<?php
declare(strict_types=1);

namespace HCaptcha\Test\TestCase;

use Cake\Event\EventManager;
use Cake\Http\Client\Request;
use Cake\Http\TestSuite\HttpClientTrait;
use Cake\TestSuite\TestCase;
use HCaptcha\Validation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * Validation tests
 */
#[UsesClass(Validation::class)]
#[CoversClass(Validation::class)]
class ValidationTest extends TestCase
{
    use HttpClientTrait;

    public function testHcaptchaResponseFail(): void
    {
        $this->mockClientPost('https://hcaptcha.com/siteverify', $this->newClientResponse(403));

        EventManager::instance()->on('HttpClient.beforeSend', function ($event, Request $request) {
            parse_str((string)$request->getBody(), $data);
            $this->assertEquals([
                'secret' => 'hcaptcha-secret',
                'response' => 'testing-post-fail',
            ], $data);
        });

        $result = Validation::hcaptcha('testing-post-fail');
        $this->assertFalse($result);
    }

    public function testHcaptchaSuccessNotSet(): void
    {
        $this->mockClientPost('https://hcaptcha.com/siteverify', $this->newClientResponse(200, [], 'false'));
        $this->mockClientPost('https://hcaptcha.com/siteverify', $this->newClientResponse(200, [], '"response"'));

        $result = Validation::hcaptcha('testing-success');
        $this->assertFalse($result);

        $result = Validation::hcaptcha('testing-success');
        $this->assertFalse($result);
    }

    public function testHcaptchaSuccess(): void
    {
        $this->mockClientPost('https://hcaptcha.com/siteverify', $this->newClientResponse(200, [], json_encode(['success' => true])));

        $result = Validation::hcaptcha('testing-success');
        $this->assertTrue($result);
    }
}
