<?php

declare(strict_types=1);

error_reporting(-1);

$rootDir = dirname(__DIR__, 2);
$vendorDir = $rootDir . '/' . 'vendor';

defined('YII_DEBUG') || define('YII_DEBUG', false);
define('YII_ENV', 'test');

$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

if (!file_exists($vendorDir . '/autoload.php')) {
    die('Please run composer install');
}

require_once $vendorDir . '/autoload.php';
require_once $vendorDir . '/yiisoft/yii2/Yii.php';
