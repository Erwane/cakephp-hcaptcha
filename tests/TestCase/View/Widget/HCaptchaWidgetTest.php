<?php
declare(strict_types=1);

namespace HCaptcha\Test\TestCase\View\Widget;

use Cake\Core\Configure;
use Cake\Log\Engine\ArrayLog;
use Cake\Log\Log;
use Cake\TestSuite\TestCase;
use Cake\View\Form\ArrayContext;
use Cake\View\StringTemplate;
use Cake\View\View;
use HCaptcha\View\Widget\HCaptchaWidget;

/**
 * Class HCaptchaWidgetTest
 *
 * @package HCaptcha\Test\TestCase\View\Widget
 * @uses \HCaptcha\View\Widget\HCaptchaWidget
 * @coversDefaultClass \HCaptcha\View\Widget\HCaptchaWidget
 */
class HCaptchaWidgetTest extends TestCase
{
    /**
     * @var \HCaptcha\View\Widget\HCaptchaWidget
     */
    private $widget;

    /**
     * @var \Cake\View\Helper\FormHelper|\PHPUnit\Framework\MockObject\MockObject
     */
    private $form;

    /**
     * @var \Cake\View\Helper\HtmlHelper|\PHPUnit\Framework\MockObject\MockObject
     */
    private $html;

    protected function setUp(): void
    {
        parent::setUp();

        $templates = new StringTemplate();
        $view = $this->createMock(View::class);

        $this->form = $this->createPartialMock('Cake\View\Helper\FormHelper', ['unlockField']);
        $this->html = $this->createPartialMock('Cake\View\Helper\HtmlHelper', ['script']);

        $view->Form = $this->form;
        $view->Html = $this->html;

        $this->widget = new HCaptchaWidget($templates, $view);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::render
     */
    public function testRenderWithoutJs(): void
    {
        $context = new ArrayContext([]);

        $this->form->expects($this->exactly(2))
            ->method('unlockField')
            ->withConsecutive(['field'], ['g-recaptcha-response']);

        $this->html->expects($this->never())->method('script');

        $result = $this->widget->render(['fieldName' => 'field', 'withoutJs' => true], $context);
        $this->assertSame('<div class="h-captcha" data-sitekey=""></div>', $result);
    }

    /**
     * @test
     * @covers ::render
     */
    public function testRenderNoKey(): void
    {
        $context = new ArrayContext([]);
        $log = new ArrayLog();
        Log::setConfig('error', $log);

        $this->widget->render(['fieldName' => 'field'], $context);
        $this->assertSame(['error: Configure HCaptcha.key in your app_local.php file'], $log->read());
    }

    /**
     * @test
     * @covers ::render
     */
    public function testRenderSimple(): void
    {
        Configure::write('HCaptcha.key', 'testing-site-key');
        $context = new ArrayContext([]);

        $this->html->expects($this->once())
            ->method('script')
            ->with('https://hcaptcha.com/1/api.js', ['block' => 'script', 'async', 'defer']);

        $result = $this->widget->render(['fieldName' => 'field'], $context);
        $this->assertSame('<div class="h-captcha" data-sitekey="testing-site-key"></div>', $result);
    }

    /**
     * @test
     * @covers ::render
     */
    public function testRenderWithOptions(): void
    {
        Configure::write('HCaptcha.key', 'testing-site-key');
        $context = new ArrayContext([]);

        $this->html->expects($this->once())
            ->method('script')
            ->with(
                'https://hcaptcha.com/1/api.js?onload=myFunction&render=explicit&hl=fr&recaptchacompat=off',
                ['block' => 'script', 'async', 'defer']
            );

        $result = $this->widget->render([
            'fieldName' => 'field',
            'lang' => 'fr_FR',
            'onload' => 'myFunction',
            'render' => 'explicit',
            'recaptchacompat' => false,
        ], $context);
        $this->assertSame('<div class="h-captcha" data-sitekey="testing-site-key"></div>', $result);
    }

    /**
     * @test
     * @covers ::secureFields
     */
    public function testSecureFields(): void
    {
        $this->assertSame([], $this->widget->secureFields(['name' => 'testing']));
    }
}
