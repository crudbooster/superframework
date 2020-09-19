<?php

namespace System;

use Dotenv\Dotenv;
use FastRoute\RouteCollector;
use System\Commands\CommandRunner;

class Super
{
    private $config;
    private $bootstrapCache;

    public function __construct()
    {
        ini_set("display_errors", 0);
        ini_set("display_startup_errors", 0);
        ini_set("error_log", base_path("error.log"));

        $this->loadEnv();

        $this->config = include base_path("configs/App.php");
        $this->bootstrapCache = include base_path("bootstrap/cache.php");
    }

    private function loadEnv() {
        Dotenv::createImmutable(base_path())->load();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function responseBuilder() {
        $dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
            $r->addGroup("/".base_path_uri(), function (RouteCollector $r) {
                foreach ($this->bootstrapCache['route'] as $pattern => $value) {
                    if($pattern == "/" || $pattern == "") {
                        $route = "/";
                    } else {
                        $route = "/".trim($pattern,"/");
                    }
                    $r->addRoute(['GET','POST'], $route,$value[0]."@".$value[1]);
                }
            });
        });

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        $response = null;

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new \Exception("The page is not found!", 404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                throw new \Exception("The method is not allowed!", 405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                list($class, $method) = explode("@", $handler, 2);
                $response = call_user_func_array([new $class, $method], $vars);
                break;
        }
        return $response;
    }

    private function loadHelpers() {
        foreach($this->bootstrapCache['helper'] as $helper) require_once base_path($helper.".php");
    }

    private function middleware(callable $content) {
        $response = call_user_func($content);
        $middleware = $this->bootstrapCache['middleware'];
        if(count($middleware)) {
            foreach($middleware as $mid) {
                $response = (new $mid)->handle(function() use ($response) {
                    return $response;
                });
            }
        } else {
            return $response;
        }
        return $response;
    }

    private function boot() {
        $boot = $this->bootstrapCache['boot'];
        if(count($boot)) {
            foreach($boot as $b) {
                (new $b)->run();
            }
        }
    }

    public function commandRun($argv) {
        try {
            $response = null;

            $this->loadHelpers();

            (new CommandRunner())->run($argv);

        } catch (\Throwable $e) {
            http_response_code($e->getCode()?:500);
            logging($e);
            if($this->config['display_errors']) {
                die($e);
            } else {
                die("Something went wrong!");
            }
        }
    }


    public function run() {
        try {
            $response = null;

            $this->loadHelpers();

            $this->boot();

            $response = $this->middleware(function () {
                return $this->responseBuilder();
            });

            echo $response;

            unset($response);

        } catch (\Throwable $e) {
            http_response_code($e->getCode()?:500);

            if($this->config['logging_errors'] == "true") {
                logging($e);
            }

            if($this->config['display_errors'] == "true") {
                die($e);
            } else {
                die("Something went wrong!");
            }
        }
    }

}