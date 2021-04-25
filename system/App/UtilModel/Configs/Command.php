<?php

namespace System\App\UtilModel\Configs;


use System\App\UtilORM\ORM;
use System\Commands\OutputMessage;

class Command
{
    use OutputMessage;

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

                // Create model class
                if(!file_exists(base_path("app/Models"))) mkdir(base_path("app/Models"));
                file_put_contents(base_path("app/Models/".$model_name.".php"), $template);
                $this->success("Model class `".$table."` has been created -> /app/Models/".$model_name.".php");

                // Create repository class
                if(!file_exists(base_path("app/Repositories"))) mkdir(base_path("app/Repositories"));
                if(!file_exists(base_path("app/Repositories/{$model_name}Repository.php"))) {
                    $template = file_get_contents(base_path("system/App/UtilModel/Stubs/Repository.php.stub"));
                    $template = str_replace("RepositoryName", $model_name."Repository", $template);
                    $template = str_replace("ModelName", $model_name, $template);
                    file_put_contents(base_path("app/Repositories/".$model_name."Repository.php"), $template);
                    $this->success("Repository class {$table} has been created -> /app/Repositories/{$model_name}Repository.php");
                }

                // Create service class
                if(!file_exists(base_path("app/Services"))) mkdir(base_path("app/Services"));
                if(!file_exists(base_path("app/Services/{$model_name}Service.php"))) {
                    $template = file_get_contents(base_path("system/App/UtilModel/Stubs/Service.php.stub"));
                    $template = str_replace("ServiceName", $model_name."Service", $template);
                    $template = str_replace("RepositoryName", $model_name."Repository", $template);
                    file_put_contents(base_path("app/Services/".$model_name."Service.php"), $template);
                    $this->success("Service class {$table} has been created -> /app/Services/{$model_name}Service.php");
                }

            } else {
                print "Creating model for table `".$table."` is failed, table not found!\n";
            }
        }
    }
}