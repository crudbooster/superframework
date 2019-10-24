<?php
/**
 * To run php more faster
 * You may enable garbage collection with gc_enable();
 */
gc_disable();

/**
 * Now let composer auto load all the class
 */
require_once "vendor/autoload.php";

/**
 * Set time start to know how much time this engine can work
 */
define("SUPER_START", microtime(true));

/**
 * We want to know where this project run
 */
define("BASE_PATH", getcwd());

/**
 * Run the main class of super framework
 */
(new \System\Super())->run();