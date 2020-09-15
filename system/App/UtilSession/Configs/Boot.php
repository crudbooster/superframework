<?php

namespace System\App\UtilSession\Configs;

use System\Interfaces\BootInterface;

class Boot implements BootInterface
{
    public function run() {
        session_start();
    }
}