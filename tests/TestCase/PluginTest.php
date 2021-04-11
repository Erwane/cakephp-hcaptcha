<?php
declare(strict_types=1);

namespace HCaptcha\Test\TestCase;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use Cake\View\Helper\FormHelper;
use Cake\View\View;
use HCaptcha\Plugin;
use HCaptcha\View\Widget\HCaptchaWidget;

/**
 * Class PluginTest
 *
 * @package HCaptcha\Test\TestCase
 * @uses \HCaptcha\Plugin
 * @coversDefaultClass \HCaptcha\Plugin
 */
class PluginTest extends TestCase
{
    /**
     * @var \HCaptcha\Plugin
     */
    private $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new Plugin();
    }

    /**
     * @test
     * @covers ::initialize
     */
    public function testInitialize(): void
    {
        $listeners = EventManager::instance()->listeners('View.beforeRender');
        self::assertCount(1, $listeners);
        self::assertInstanceOf('\Hcaptcha\Plugin', $listeners[0]['callable'][0]);
        self::assertSame('addWidget', $listeners[0]['callable'][1]);
    }

    /**
     * @test
     * @covers ::addWidget
     */
    public function testAddWidgetNoFormHelper(): void
    {
        $view = new View();
        $event = new Event('View.beforeRender', $view);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $result = $this->plugin->addWidget($event);

        self::assertNull($result);
    }

    /**
     * @test
     * @covers ::addWidget
     */
    public function testAddWidget(): void
    {
        $view = new View();
        $view->loadHelper('Form', ['className' => FormHelper::class]);

        $event = new Event('View.beforeRender', $view);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->plugin->addWidget($event);

        /** @var \Cake\View\Helper\FormHelper $formHelper */
        $formHelper = $view->helpers()->get('Form');
        self::assertInstanceOf(HCaptchaWidget::class, $formHelper->getWidgetLocator()->get('hcaptcha'));
    }
}
