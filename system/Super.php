<?php

namespace System;

class Super
{
    private $config;

    public function __construct()
    {
        $this->config = include base_path("app/Configs/config.php");
        if($this->config['session_enable']===true) {
            session_name("SuperFW_".basename(base_path()));
            session_start();
        }
    }

    private function urlSlicing() {
        $args = explode('/', str_ireplace($this->config['base_url'], "", get_current_url([], false)));
        foreach($args as $i=>$arg) {
            if($arg == "index.php") {
                for($e=0;$e<=$i;$e++) unset($args[$e]);
                break;
            }
        }

        return array_filter(array_values($args));
    }

    private function controllerClassSelection($args) {
        if($args) {
            $class = $method = $param_start = $module_name = null;

            // Check to route file first
            $args_merge = implode("/", $args)."/";
            $args_count = count($args);
            $routes = include base_path("app/Configs/routes.php");
            foreach($routes as $path => $path_class) {
                $path .= "/";
                $total_split_path = count(explode("/", $path))-1;
                if($total_split_path == ($args_count-1) || $total_split_path == $args_count) {
                    if(substr($args_merge,0, strlen($path)) === $path) {
                        $class_split = explode("@", $path_class);
                        if($class_split) {
                            $class = $class_split[0];
                            $method = $class_split[1];
                            $param_start = count(explode("/", $path));
                        }
                    }
                }
            }


            if(!$class && !$method) {
                if(isset($args[0]) && isset($args[1]) && isset($args[2])) {
                    $class = "\App\Modules\\".ucfirst($args[0])."\\Controllers\\".ucfirst($args[1]);
                    $method = $args[2];
                    $param_start = 3;
                } elseif (isset($args[0]) && isset($args[1]) && !isset($args[2])) {
                    $class = "\App\Modules\\" . ucfirst($args[0]) . "\\Controllers\\" . ucfirst($args[1]);
                    $method = "index";
                    $param_start = 2;
                } elseif (isset($args[0]) && !isset($args[1]) && !isset($args[2])) {
                    $class = $method = $module_name = $param_start = null;
                }  else {
                    $class = $method = $module_name = $param_start = null;
                }
            }

        } else {
            $class = $this->config['default_controller'];
            $method = "index";
            $param_start = 1;
        }

        $obj = new \stdClass();
        $obj->method = $method;
        $obj->class = $class;
        $obj->param_start = $param_start;
        return $obj;
    }

    private function measureEnd($response) {
        if($this->config['consume_time_process'] === true) {
            $time = microtime(true) - SUPER_START;
            $content_type = get_header_content("Content-Type")?:"text/html";
            if($content_type == "text/html") {
                $response .= "\n<!-- Consume time: ".$time." s -->";
            }
        }
        return $response;
    }

    private function responseBuilder($selection, $args) {
        $class = $selection->class;
        if(method_exists($selection->class, $selection->method)) {
            // Get method params
            $args = array_slice($args, $selection->param_start-1);
            $args = array_values($args);
            if(count($args)==1) {
                $response = (new $class())->{$selection->method}($args[0]);
            } elseif (count($args) == 2) {
                $response = (new $class())->{$selection->method}($args[0], $args[1]);
            } elseif (count($args) == 3) {
                $response = (new $class())->{$selection->method}($args[0], $args[1], $args[2]);
            } elseif (count($args) == 4) {
                $response = (new $class())->{$selection->method}($args[0], $args[1], $args[2], $args[3]);
            } elseif (count($args) == 5) {
                $response = (new $class())->{$selection->method}($args[0], $args[1], $args[2], $args[3], $args[4]);
            } else {
                $response = (new $class())->{$selection->method}();
            }
        } else {
            http_response_code(404);
            $response = rtrim(include "Views/error/404.php","1");
        }

        return $response;
    }

    private function CSRFProtection() {
        if(request_is_post()) {
            $csrf_config = config("csrf_exception");
            foreach($csrf_config as $pattern) {
                if(!preg_match("/{$pattern}(\/|$)/i", str_ireplace(config("base_url"),"",get_current_url()) )) {
                    if(!csrf_validation()) {
                        abort(400,"Submit aborted, csrf token invalid!");
                    }
                }
            }
        }
    }

    private function middleware(callable $process) {
        $middleware = include base_path("app/Configs/middleware.php");
        $response = null;
        foreach($middleware as $middle) {
            $response = call_user_func((new $middle())->handle(function() use ($process) {
                // Check CSRF Security
                $this->CSRFProtection();

                // Run the process
                return $process;
            }));
        }
        return $response;
    }

    public function run() {

        ini_set("display_errors",0);
        ini_set("display_startup_errors", 0);

        $args = $this->urlSlicing();
        $selection = $this->controllerClassSelection($args);

        $response = $this->middleware(function() use ($selection, $args) {
            if($selection && $selection->class && $selection->method) {
                $response = $this->responseBuilder($selection, $args);
            } else {
                http_response_code(404);
                $response = rtrim(include "Views/error/404.php","1");
            }

            $response = $this->measureEnd($response);
            return $response;
        });

        try {
            echo call_user_func($response);
        } catch (\Error $e) {
            http_response_code(500);
            logging($e);
            exit;
        }
    }

}