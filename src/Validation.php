<?php
declare(strict_types=1);

namespace HCaptcha;

use Cake\Core\Configure;
use Cake\Http\Client;

/**
 * Class Validation
 *
 * @package HCaptcha
 */
class Validation
{
    /**
     * Validate captcha
     *
     * @param string $check h-captcha-response
     * @return bool
     */
    public static function hcaptcha(string $check): bool
    {
        $data = [
            'secret' => Configure::read('HCaptcha.secret'),
            'response' => $check,
        ];

        $client = new Client(['timeout' => 3, 'protocolVersion' => '2']);

        $response = $client->post('https://hcaptcha.com/siteverify', $data);

        if (!$response->isSuccess()) {
            return false;
        }

        $json = $response->getJson();

        if (!isset($json['success'])) {
            return false;
        }

        return $json['success'];
    }
}
