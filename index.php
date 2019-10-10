<?php require_once "vendor/autoload.php";

/**
 * Set time start to know how much time this engine can work
 */
define("SUPER_START", microtime(true));

/**
 * Run the main class of super framework
 */
(new \System\Super())->run();