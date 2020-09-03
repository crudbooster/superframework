<?php

namespace System\Commands;


use System\Foundation\Command;
use System\Helpers\RouteParser;

class Compile extends Command
{

    /**
     * @throws \ReflectionException
     */
    public function run() {
        $this->compileRoutes();
        $this->compileBoot();
        $this->compileMiddlewares();
        $this->compileHelpers();
        $this->compileCommand();
        print "System has been recompiled!";
    }

    /**
     * @throws \ReflectionException
     */
    private function compileRoutes() {
        RouteParser::generateRoute();
    }

    private function compileCommand() {
        $files = glob(base_path("app/{,*/,*/*/,*/*/*/}Configs/Command.php"), GLOB_BRACE);
        foreach($files as &$file) {
            $file = RouteParser::cleanClassName($file);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['command'] = $files;
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }

    private function compileBoot() {
        $files = glob(base_path("app/{,*/,*/*/,*/*/*/}Configs/Boot.php"), GLOB_BRACE);
        foreach($files as &$file) {
            $file = RouteParser::cleanClassName($file);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['boot'] = $files;
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }

    private function compileMiddlewares() {
        $middlewares = glob(base_path("app/{,*/,*/*/,*/*/*/}Configs/Middleware.php"), GLOB_BRACE);
        foreach($middlewares as &$middleware) {
            $middleware = RouteParser::cleanClassName($middleware);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['middleware'] = $middlewares;
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }

    private function compileHelpers() {
        $middlewares = glob(base_path("app/{,*/,*/*/,*/*/*/}Configs/Helper.php"), GLOB_BRACE);
        foreach($middlewares as &$middleware) {
            $middleware = RouteParser::cleanClassName($middleware);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['helper'] = $middlewares;
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }
}