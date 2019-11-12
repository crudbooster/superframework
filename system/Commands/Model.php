<?php

namespace System\Commands;

use System\ORM\ORM;

class Model extends Command
{

    public function run() {

        $orm = new ORM();

        $model_data = include base_path("app/Configs/model.php");
        foreach($model_data as $model_name=>$model) {
            $table_name = $model['table'];
            if($orm->hasTable($table_name)) {
                $template = file_get_contents(base_path("system/Models/Model.php.stub"));
                $template = str_replace("ModelName", $model_name, $template);

                $todo = null;
                foreach($model['columns'] as $column) {
                    $todo .= "\t".'private $'.$column['name'].';'."\n";
                }

                $todo .= "\n\n";
                foreach($model['columns'] as $column) {
                    $todo .= "\tpublic function set".convert_snake_to_CamelCase($column['name'], true)."(\$value) {\n";
                    $todo .= "\t\t".'$this->'.$column['name'].' = $value;'."\n";
                    $todo .= "\t}\n\n";

                    $todo .= "\tpublic function get".convert_snake_to_CamelCase($column['name'], true)."() {\n";
                    $todo .= "\t\t".'return $this->'.$column['name'].';'."\n";
                    $todo .= "\t}\n\n";
                }

                $template = str_replace("//Todo", $todo, $template);

                file_put_contents(base_path("app/Models/".$model_name.".php"), $template);

                print "Model table `".$table_name."` has been created!\n";
            } else {
                print "Creating model for table `".$table_name."` is failed, table not found!\n";
            }
        }
    }

}