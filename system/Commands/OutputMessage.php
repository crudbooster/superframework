<?php


namespace System\Commands;


trait OutputMessage
{

    public function defaultForeground($msg = null, $inline = false)
    {
        echo "\033[39m".$msg . ((!$inline) ? "\n" :"");
    }

    public function success($msg, $inline = false)
    {
        echo "\033[32m ".$msg. ((!$inline) ? "\n" :"");
        $this->defaultForeground( null, true);
    }

    public function info($msg, $inline = false)
    {
        echo "\033[34m ".$msg. ((!$inline) ? "\n" :"");
        $this->defaultForeground( null, true);
    }

    public function danger($msg, $inline = false)
    {
        echo "\033[31m ". $msg. ((!$inline) ? "\n" :"");
        $this->defaultForeground( null, true);
    }

    public function warning($msg, $inline = false)
    {
        echo "\033[33m ". $msg. ((!$inline) ? "\n" :"");
        $this->defaultForeground( null, true);
    }

}