<?php
declare(strict_types=1);

namespace HCaptcha;

use Cake\Core\Configure;
use Cake\Http\Client;
use Psr\Http\Client\ClientInterface;

/**
 * Class Validation
 *
 * @package HCaptcha
 */
class Validation
{
    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    protected static $_client;

    /**
     * Set Http client interface
     *
     * @param  \Psr\Http\Client\ClientInterface $client Http client interface
     * @return void
     */
    public static function setClient(ClientInterface $client): void
    {
        self::$_client = $client;
    }

    /**
     * Get Http client
     *
     * @return \Cake\Http\Client|\Psr\Http\Client\ClientInterface
     */
    public static function getClient(): ClientInterface
    {
        if (!isset(static::$_client)) {
            static::$_client = new Client(['timeout' => 3, 'protocolVersion' => '2']);
        }

        return static::$_client;
    }

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

        $client = self::getClient();

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
