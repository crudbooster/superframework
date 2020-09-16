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
        RouteParser::generateRoute();

        // System App Compiling
        $this->compileBoot("system/App/");
        $this->compileMiddlewares("system/App/");
        $this->compileHelpers("system/App/");
        $this->compileCommand("system/App/");

        // User App Compiling
        $this->compileBoot("app/");
        $this->compileMiddlewares("app/");
        $this->compileHelpers("app/");
        $this->compileCommand("app/");

        print "Application has been recompiled!";
    }

    private function compileCommand($prefix_path = "app/") {
        $files = glob(base_path($prefix_path."{,*/,*/*/,*/*/*/}Configs/Command.php"), GLOB_BRACE);
        foreach($files as $i=>$file) {
            $files[$i] = RouteParser::cleanClassName($file);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['command'] = array_merge($boots['command'],$files);
        $boots['command'] = array_unique($boots['command']);
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }

    private function compileBoot($prefix_path = "app/") {
        $files = glob(base_path($prefix_path."{,*/,*/*/,*/*/*/}Configs/Boot.php"), GLOB_BRACE);
        foreach($files as &$file) {
            $file = RouteParser::cleanClassName($file);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['boot'] = array_merge($boots['boot'],$files);
        $boots['boot'] = array_unique($boots['boot']);
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }

    private function compileMiddlewares($prefix_path = "app/") {
        $middlewares = glob(base_path($prefix_path."{,*/,*/*/,*/*/*/}Configs/Middleware.php"), GLOB_BRACE);
        foreach($middlewares as &$middleware) {
            $middleware = RouteParser::cleanClassName($middleware);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['middleware'] = array_merge($boots['middleware'],$middlewares);
        $boots['middleware'] = array_unique($boots['middleware']);
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }

    private function compileHelpers($prefix_path = "app/") {
        $files = glob(base_path($prefix_path."{,*/,*/*/,*/*/*/}Configs/Helper.php"), GLOB_BRACE);
        foreach($files as &$file) {
            $file = RouteParser::cleanClassName($file);
        }
        $boots = include base_path("bootstrap/cache.php");
        $boots['helper'] = array_merge($boots['helper'],$files);
        $boots['helper'] = array_unique($boots['helper']);
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".var_min_export($boots, true).";");
    }
}