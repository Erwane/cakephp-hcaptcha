<?php
declare(strict_types=1);

namespace HCaptcha\Test\TestCase;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use Cake\View\Helper\FormHelper;
use Cake\View\View;
use HCaptcha\HCaptchaPlugin;
use HCaptcha\View\Widget\HCaptchaWidget;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * HCaptchaPlugin tests
 */
#[UsesClass(HCaptchaPlugin::class)]
#[CoversClass(HCaptchaPlugin::class)]
class HCaptchaPluginTest extends TestCase
{
    /**
     * @var \HCaptcha\HCaptchaPlugin
     */
    private $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new HCaptchaPlugin();
    }

    public function testInitialize(): void
    {
        $listeners = EventManager::instance()->listeners('View.beforeRender');
        $this->assertCount(1, $listeners);
    }

    public function testAddWidgetNoFormHelper(): void
    {
        $view = new View();
        $event = new Event('View.beforeRender', $view);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $result = $this->plugin->addWidget($event);

        $this->assertNull($result);
    }

    public function testAddWidget(): void
    {
        $view = new View();
        $view->loadHelper('Form', ['className' => FormHelper::class]);

        $event = new Event('View.beforeRender', $view);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->plugin->addWidget($event);

        /** @var \Cake\View\Helper\FormHelper $formHelper */
        $formHelper = $view->helpers()->get('Form');
        $this->assertInstanceOf(HCaptchaWidget::class, $formHelper->getWidgetLocator()->get('hcaptcha'));
    }
}
