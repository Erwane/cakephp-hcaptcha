<?php
declare(strict_types=1);

namespace HCaptcha\View\Widget;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use Cake\View\View;
use Cake\View\Widget\WidgetInterface;

/**
 * Class HCaptchaWidget
 *
 * @package HCaptcha\View\Widget
 */
class HCaptchaWidget implements WidgetInterface
{
    /**
     * @var \Cake\View\StringTemplate
     */
    protected $_templates;

    /**
     * @var \Cake\View\View
     */
    private $_view;

    /**
     * HCaptchaWidget constructor.
     *
     * @param  \Cake\View\StringTemplate $templates String templates
     * @param  \Cake\View\View $view Cake view
     */
    public function __construct(StringTemplate $templates, View $view)
    {
        $this->_templates = $templates;

        $this->_view = $view;
    }

    /**
     * Render HCaptcha div and append javascript call to script block
     *
     * @param  array $data The data to render.
     * @param  \Cake\View\Form\ContextInterface $context The current form context.
     * @return string Generated HTML for the widget element.
     */
    public function render(array $data, ContextInterface $context): string
    {
        $data += [
            'fieldName' => '',
            'withoutJs' => false,
        ];

        $this->_view->Form->unlockField($data['fieldName']);
        $this->_view->Form->unlockField('g-recaptcha-response');

        // Append js
        if (!$data['withoutJs']) {
            $this->_view->Html->script('https://hcaptcha.com/1/api.js', ['block' => 'script']);
        }

        $key = Configure::read('HCaptcha.key');
        if (!$key) {
            Log::error('Configure HCaptcha.key in your app_local.php file');
        }

        return '<div class="h-captcha" data-sitekey="' . $key . '"></div>';
    }

    /**
     * No field should be secured
     *
     * @param  array $data The data to render.
     * @return string[] Array of fields to secure.
     */
    public function secureFields(array $data): array
    {
        return [];
    }
}
