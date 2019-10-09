<?php

namespace System\Commands;


class Route extends Command
{

    /**
     * @param $route
     * @param $class_name
     * @param $doc
     * @param null $prefix
     * @return \stdClass
     */
    private function assignRoute($route, $class_name, $doc, $prefix = null) {
        //perform the regular expression on the string provided
        preg_match_all("#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#", $doc, $matches, PREG_PATTERN_ORDER);

        $route_path = null;
        foreach($matches as $match) {
            foreach($match as $m) {
                if(substr($m,0,6)  == "@route") {
                    $route_path = trim(substr($m, 7));
                    $route_path = ($prefix)?$prefix."/".$route_path:$route_path;
                    $route[$route_path] = $class_name;
                    break;
                }
            }
        }

        $resp = new \stdClass();
        $resp->route = $route;
        $resp->route_path = $route_path;
        return $resp;
    }


    public function run() {
        $route = [];
        $list = glob(getcwd()."/app/Modules/{,*/,*/*/,*/*/*/}Controllers/*.php", GLOB_BRACE);
        foreach($list as $item) {
            $class_name = $this->makeClassName($item);
            echo $class_name." [added]\n";
            try {
                $reflect = new \ReflectionClass($class_name);
                $doc = $reflect->getDocComment();

                $class_route = $this->assignRoute($route, $class_name."@index", $doc);
                $route = $class_route->route;

                foreach($reflect->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                    $doc = $method->getDocComment();
                    $method_name = $method->getName();
                    $method_route = $this->assignRoute($route, $class_name."@".$method_name, $doc, $class_route->route_path);
                    $route = $method_route->route;
                }

            } catch (\ReflectionException $e) {
                echo "err: ".$e->getMessage()."\n";
            }
        }

        $route_data = var_min_export($route, true);

        file_put_contents(getcwd()."/app/Configs/routes.php", "<?php\n\nreturn ".$route_data.";");

        print "Route has been generated!\n\n";
    }

}