<?php

namespace App\Modules\Main\Configs;

use App\Providers\AppServiceProvider;
use SuperFrameworkEngine\Interfaces\BootInterface;

class Boot implements BootInterface
{
    public function run()
    {
        (new AppServiceProvider())->run();
    }
}
