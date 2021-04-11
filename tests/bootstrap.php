<?php
declare(strict_types=1);

namespace HCaptcha\Test;

use Cake\Core\Configure;
use Cake\Utility\Security;

require dirname(__DIR__) . '/vendor/autoload.php';

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// Path constants to a few helpful things.
define('ROOT', dirname(__DIR__) . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TMP', sys_get_temp_dir() . DS);
define('CACHE', TMP);
define('LOGS', TMP);

require CORE_PATH . 'config' . DS . 'bootstrap.php';

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

Configure::write('debug', true);
Configure::write('HCaptcha.secret', 'hcaptcha-secret');
Security::setSalt('a-long-but-not-random-value');
