<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/3/2020
 * Time: 2:20 PM
 */

namespace System\Helpers;


use ReflectionException;

class RouteParser
{
    static function cleanClassName($class_file) {
        $class_name = str_replace(base_path(),"",$class_file);
        $class_name = str_replace("/","\\", $class_name);
        $class_name = str_replace("system","System", $class_name);
        $class_name = str_replace("app\\","App\\", $class_name);
        $class_name = str_replace(".php","", $class_name);
        return $class_name;
    }

    /**
     * @throws ReflectionException
     */
    static function generateRoute() {
        $result = [];
        $list = glob(base_path("app/{,*/,*/*/,*/*/*/}Controllers/*.php"), GLOB_BRACE);
        foreach($list as $item) {
            $class_name = static::cleanClassName($item);
            $reflect = new \ReflectionClass($class_name);
            $doc = $reflect->getDocComment();
            $classRoute = static::getRouteDoc($doc);

            foreach($reflect->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $doc = $method->getDocComment();
                $methodRoute = static::getRouteDoc($doc, $classRoute)?:"/";
                $method_name = $method->getName();
                $result[$methodRoute] = [$class_name,$method_name];
            }
        }

        $list = glob(base_path("system/App/{,*/,*/*/,*/*/*/}Controllers/*.php"), GLOB_BRACE);
        foreach($list as $item) {
            $class_name = static::cleanClassName($item);
            $reflect = new \ReflectionClass($class_name);
            $doc = $reflect->getDocComment();
            $classRoute = static::getRouteDoc($doc);

            foreach($reflect->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $doc = $method->getDocComment();
                $methodRoute = static::getRouteDoc($doc, $classRoute)?:"/";
                $method_name = $method->getName();
                $result[$methodRoute] = [$class_name,$method_name];
            }
        }

        $bootstrap = include base_path("bootstrap/cache.php");
        $bootstrap['route'] = $result;
        $bootstrap = var_min_export($bootstrap, true);

        if(file_exists(base_path('bootstrap/route.cache'))) unlink(base_path('bootstrap/route.cache'));
        file_put_contents(base_path('bootstrap/cache.php'), "<?php\n\nreturn ".$bootstrap.";");
    }

    static function getRouteDoc($doc, $prefix = null) {
        //perform the regular expression on the string provided
        preg_match_all("#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#", $doc, $matches, PREG_PATTERN_ORDER);

        $route_path = null;
        foreach($matches as $match) {
            foreach($match as $m) {
                if(substr($m,0,6)  == "@route") {
                    $route_path = trim(substr($m, 7));
                    $route_path = ($prefix)?$prefix."/".$route_path:$route_path;
                    break;
                }
            }
        }

        return ($route_path=="/")?null:trim($route_path,"/");
    }

}