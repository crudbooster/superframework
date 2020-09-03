<?php

namespace System;

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

        $this->config = include base_path("configs/App.php");
        $this->bootstrapCache = include base_path("bootstrap/cache.php");
    }

    private function parseUrl() {
        $request_uri = str_replace([base_url("index.php"),base_url()],"", get_current_url());
        $args = explode('/', $request_uri);
        return array_values(array_filter($args));
    }

    private function findRoute($args_str) {
        $route_found = null;
        $route_arguments = [];
        foreach($this->bootstrapCache['route'] as $pattern => $value) {
            preg_match("/".$pattern."/", $args_str, $output);
            if($output) {
                $route_found = $value;
                $route_arguments = array_slice($output,1);
                break;
            }
        }
        return [$route_found, $route_arguments];
    }

    /**
     * @param $args
     * @return \stdClass
     * @throws \Exception
     */
    private function controllerClassSelection($args) {
        if($args) {
            $args_str = implode("/",$args);
            $route = $this->findRoute($args_str);
            $route_found = $route[0];
            $route_arguments = $route[1];

            if(!$route_found) {
                throw new \Exception("The route `".$args_str."` is not found!", 404);
            }

            $class = $route_found[0];
            $method = $route_found[1];
            $arguments = $route_arguments;
        } else {
            $class = $this->config['default_controller'];
            $method = $this->config['default_method'];
            $arguments = [];
        }

        $obj = new \stdClass();
        $obj->method = $method;
        $obj->class = $class;
        $obj->arguments = $arguments;
        return $obj;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function responseBuilder() {
        $selection = $this->controllerClassSelection($this->parseUrl());
        $class = $selection->class;
        $response = call_user_func_array([new $class, $selection->method],$selection->arguments);
        return $response;
    }

    private function loadHelpers() {
        foreach($this->bootstrapCache['helper'] as $helper) require_once base_path($helper.".php");
    }

    private function middleware(callable $callback) {
        $response = null;
        $middleware = $this->bootstrapCache['middleware'];
        if(count($middleware)) {
            foreach($middleware as $mid) {
                $response = (new $mid)->handle(function() use ($callback) {
                    $response = call_user_func($callback);
                    return $response;
                });
            }
        } else {
            $response = call_user_func($callback);
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

}