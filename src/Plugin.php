<?php
declare(strict_types=1);

namespace HCaptcha;

use Cake\Core\BasePlugin;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;

/**
 * Class Plugin
 *
 * @package HCaptcha
 */
class Plugin extends BasePlugin
{
    /**
     * Initialize plugin.
     * Add View.beforeRender listener to add widget in FormHelper.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        EventManager::instance()->on('View.beforeRender', [$this, 'addWidget']);
    }

    /**
     * Add HCaptcha widget to FormHelper, if exists.
     *
     * @param  \Cake\Event\EventInterface $event Dispatched event
     * @return void
     */
    public function addWidget(EventInterface $event): void
    {
        /** @var \Cake\View\View $view */
        $view = $event->getSubject();

        // No Form helper ? skip
        if (!$view->helpers()->has('Form')) {
            return;
        }

        /** @var \Cake\View\Helper\FormHelper $formHelper */
        $view->helpers()->get('Form')
            ->addWidget('hcaptcha', ['HCaptcha.HCaptcha', '_view']);
    }
}
