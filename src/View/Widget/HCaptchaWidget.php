<?php
declare(strict_types=1);

namespace HCaptcha\View\Widget;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use Cake\View\View;
use Cake\View\Widget\WidgetInterface;
use Laminas\Diactoros\Uri;
use Locale;

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
     * @var string
     */
    protected $_apiUrl = 'https://hcaptcha.com/1/api.js';

    /**
     * @var string[]
     */
    protected $_renderAllowedValues = ['explicit', 'onload'];

    /**
     * HCaptchaWidget constructor.
     *
     * @param \Cake\View\StringTemplate $templates String templates
     * @param \Cake\View\View $view Cake view
     */
    public function __construct(StringTemplate $templates, View $view)
    {
        $this->_templates = $templates;

        $this->_view = $view;
    }

    /**
     * Render HCaptcha div and append javascript call to script block
     *
     * @param array $data The data to render.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string Generated HTML for the widget element.
     */
    public function render(array $data, ContextInterface $context): string
    {
        $data += [
            'fieldName' => '',
            'withoutJs' => false,
            'onload' => null,
            'render' => null,
            'lang' => null,
            'recaptchacompat' => null,
        ];

        $this->_view->Form->unlockField($data['fieldName']);
        $this->_view->Form->unlockField('g-recaptcha-response');

        // Append js
        if (!$data['withoutJs']) {
            $uri = new Uri($this->_apiUrl);
            $queryArgs = [];

            if ($data['onload']) {
                $queryArgs['onload'] = h($data['onload']);
            }
            if ($data['render'] && in_array($data['render'], $this->_renderAllowedValues)) {
                $queryArgs['render'] = h($data['render']);
            }

            if ($data['lang']) {
                $locale = Locale::parseLocale((string)$data['lang']);
                if (!empty($locale['language'])) {
                    $queryArgs['hl'] = $locale['language'];
                }
            }

            if ($data['recaptchacompat'] !== null) {
                $queryArgs['recaptchacompat'] = in_array(
                    $data['recaptchacompat'],
                    [1, '1', 'y', 'Y', 'yes', 'on']
                ) ? 'on' : 'off';
            }

            $url = $uri->withQuery(http_build_query($queryArgs))
                ->__toString();

            $this->_view->Html->script($url, ['block' => 'script', 'async', 'defer']);
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
     * @param array $data The data to render.
     * @return string[] Array of fields to secure.
     */
    public function secureFields(array $data): array
    {
        return [];
    }
}
