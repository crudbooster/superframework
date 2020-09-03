<?php
/**
 * Now let composer auto load all the class
 */
require_once "../vendor/autoload.php";

/**
 * Set time start to know how much time this engine can work
 */
define("SUPER_START", microtime(true));

/**
 * We want to know where this project run
 */
define("BASE_PATH", realpath(getcwd().DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR));
define("BASE_DIR", dirname(__FILE__));

/**
 * Run the main class of super framework
 */
(new \System\Super())->run();