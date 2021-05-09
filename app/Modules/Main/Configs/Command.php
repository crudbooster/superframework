<?php


namespace App\Modules\Main\Configs;


use SuperFrameworkEngine\Commands\OutputMessage;

class Command
{
    use OutputMessage;

    /**
     * @description Test Command
     * @command test
     */
    public function run() {
        $this->info("Yes it works!");
    }
}