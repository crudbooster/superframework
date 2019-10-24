<?php

namespace System\Commands;

class Module extends Command
{

    public function run($module_name) {
        $module_name = str_replace(" ","",ucwords($module_name));
        $controller_stub = file_get_contents(base_path("system/Stubs/Module/Controller.php.stub"));
        $controller_stub = str_replace("ModuleName", $module_name, $controller_stub);

        $view_stub = file_get_contents(base_path("system/Stubs/Module/view.php.stub"));
        if(!file_exists(base_path("app/Modules/".$module_name))) {
            mkdir(base_path("app/Modules/".$module_name));
        }

        if(!file_exists(base_path("app/Modules/".$module_name."/Controllers"))) {
            mkdir(base_path("app/Modules/".$module_name."/Controllers"));
        }

        if(!file_exists(base_path("app/Modules/".$module_name."/Views"))) {
            mkdir(base_path("app/Modules/".$module_name."/Views"));
        }

        file_put_contents(base_path("app/Modules/".$module_name."/Controllers/".$module_name.".php"), $controller_stub);
        file_put_contents(base_path("app/Modules/".$module_name."/Views/index.php"), $view_stub);

        print "Module `".$module_name."` has been created!";
    }

}