<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10/8/2019
 * Time: 12:11 PM
 */

namespace System\Commands;


class Route
{
    /**
     * @param $expression
     * @param bool $return
     * @return mixed|null|string|string[]
     */
    private function var_min_export($expression, $return=FALSE) {
        $export = var_export($expression, TRUE);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
        $export = join(PHP_EOL, array_filter(["["] + $array));
        if ((bool)$return) return $export; else echo $export;
    }

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

    /**
     * @param $class_file
     * @return mixed|string
     */
    private function makeClassName($class_file) {
        $class_name = str_replace(getcwd(),"",$class_file);
        $class_name = str_replace("/","\\", $class_name);
        $class_name = str_replace("\app","\App", $class_name);
        $class_name = rtrim($class_name, ".php");
        return $class_name;
    }

    public function run() {
        $route = [];
        $list = glob(getcwd()."/app/Modules/{,*/,*/*/,*/*/*/}Controllers/*.php", GLOB_BRACE);
        foreach($list as $item) {
            $class_name = $this->makeClassName($item);
            echo $class_name."\n";
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
                echo "err: ".$e->getMessage();
            }
        }

        $route_data = $this->var_min_export($route, true);

        file_put_contents(getcwd()."/app/Configs/routes.php", "<?php\n\nreturn ".$route_data.";");

        print "Route has been generated!";
    }

}