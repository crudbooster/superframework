<?php


namespace System\Commands;


trait CommandCore
{

    /**
     * @throws \ReflectionException
     */
    public function getListCommand($list) {
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

    public function parseDoc(\ReflectionClass $reflectionClass) {
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