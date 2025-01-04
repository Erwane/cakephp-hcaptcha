<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\Utility\Security;

require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT', dirname(__DIR__) . DS);
const CORE_PATH = ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS;
const CAKE = CORE_PATH . 'src' . DS;
require CAKE . 'Core/functions_global.php';

Configure::write('debug', true);
Configure::write('App.encoding', 'UTF-8');
Configure::write('HCaptcha.secret', 'hcaptcha-secret');
Security::setSalt('a-long-but-not-random-value');
