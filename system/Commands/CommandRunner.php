<?php

namespace System\Commands;


class CommandRunner
{
    private $bootstrap;

    public function __construct()
    {
        $this->bootstrap = include base_path("bootstrap/cache.php");
    }

    /**
     * @param $argv
     * @throws \ReflectionException
     */
    public function run($argv) {
        @$command = $argv[1];
        @$arguments = array_slice($argv,2);
        $commands = $this->getListCommand();
        if($command) {
            foreach($commands as $c) {
                if($c['command'] == $command) {
                    $class = $c['class'];
                    $method = $c['method'];
                    if($arguments) {
                        call_user_func_array([new $class, $method], $arguments);
                    } else {
                        (new $class)->$method();
                    }
                }
            }
        } else {
            foreach($commands as $c) {
                print $c['command'].' - '.$c['description']."\n";
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function getListCommand() {
        $list = $this->bootstrap['command'];
        $result = [];

        // Add Compile Command
        $result[] = [
          'class'=> Compile::class,
          'method'=> 'run',
          'command'=> 'compile',
          'description'=> 'To compile middleware, route, boot, command, etc'
        ];

        if($list) {
            foreach($list as $item) {
                $reflect = new \ReflectionClass($item);
                $doc = $this->parseDoc($reflect);
                $result = array_merge($result, $doc);
            }
        }
        return $result;
    }

    private function parseDoc(\ReflectionClass $reflectionClass) {
        $result = [];
        foreach($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $doc = $method->getDocComment();
            //perform the regular expression on the string provided
            preg_match_all("#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#", $doc, $matches, PREG_PATTERN_ORDER);

            $command = null;
            $description = null;
            foreach($matches as $match) {
                foreach($match as $m) {
                    if(substr($m,0,8)  == "@command") {
                        $command = trim(substr($m, 9));
                    } else if(substr($m, 0, 12) == "@description") {
                        $description = trim(substr($m, 13));
                    }
                }
            }

            if($command) {
                $result[] = [
                    'method'=> $method->name,
                    'class'=> $method->class,
                    'command'=> $command,
                    'description'=> $description
                ];
            }

        }

        return $result;
    }
}