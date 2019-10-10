<?php

namespace System\Interfaces;


interface Middleware
{
    public function handle(\Closure $closure);
}