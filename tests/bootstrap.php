<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Suppress all deprecations early
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '0');

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Load engine helpers
$enginePath = BASE_PATH . '/vendor/fherryfherry/super-framework-engine/src/App';
$helpers = glob($enginePath . '/*/Configs/Helper.php');
foreach ($helpers as $helper) {
    require_once $helper;
}

// Load app helpers
require_once BASE_PATH . '/app/Helpers/General.php';

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '1');
