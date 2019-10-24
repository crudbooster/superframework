<?php

namespace System\Commands;


class Middleware extends Command
{

    public function run() {
        $route = [];
        $list = glob(getcwd()."/app/Middleware/*.php", GLOB_BRACE);
        foreach($list as $item) {
            $class = $this->makeClassName($item);
            try {
                $reflection_class = new \ReflectionClass($class);
                if($reflection_class->hasMethod("handle")) {
                    $route[] = $class;
                    echo $class." [added]\n";
                }
            } catch (\ReflectionException $e) {
                echo "err: ".$e->getMessage()."\n";
            }
        }

        $route_data = var_min_export($route, true);

        file_put_contents(getcwd()."/app/Configs/middleware.php", "<?php\n\nreturn ".$route_data.";");

        print "Middleware has been generated!\n\n";
    }

}