<?php


namespace System\Commands;


trait OutputMessage
{

    public function defaultForeground()
    {
        echo "\033[39m";
    }

    public function success($msg)
    {
        echo "\033[32m ".$msg."\n";
        $this->defaultForeground();
    }

    public function info($msg)
    {
        echo "\033[34m ".$msg."\n";
        $this->defaultForeground();
    }

    public function danger($msg)
    {
        echo "\033[31m ".$msg."\n";
        $this->defaultForeground();
    }

    public function warning($msg)
    {
        echo "\033[33m ".$msg."\n";
        $this->defaultForeground();
    }

}