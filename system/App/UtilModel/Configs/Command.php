<?php

namespace System\App\UtilModel\Configs;


use System\App\UtilORM\ORM;

class Command
{

    /**
     * @description To make model from a table
     * @command make:model
     * @param $table
     */
    public function makeModel($table = null) {
        $orm = new ORM();
        if($table) {
            $list_table = [$table];
        } else {
            $list_table = $orm->listTable();
        }

        foreach($list_table as $table) {

            $model_name = convert_snake_to_CamelCase($table,true);

            if($orm->hasTable($table)) {
                $template = file_get_contents(base_path("system/App/UtilModel/Stubs/Model.php.stub"));
                $template = str_replace("ModelName", $model_name, $template);

                $todo = null;
                $todo .= "\t".'protected $table = "'.$table.'";'."\n";
                $todo .= "\t".'protected $primaryKey = "'.$orm->findPrimaryKey($table).'";'."\n\n";
                foreach($orm->listColumn($table) as $column) {
                    $todo .= "\t".'public $'.$column.';'."\n";
                }

                $todo .= "\n\n";

                $template = str_replace("//Todo", $todo, $template);

                file_put_contents(base_path("system/App/UtilModel/Models/".$model_name.".php"), $template);

                print "Model table `".$table."` has been created -> /system/App/UtilModel/Models/".$model_name.".php\n";
            } else {
                print "Creating model for table `".$table."` is failed, table not found!\n";
            }
        }
    }
}