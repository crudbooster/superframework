<?php

namespace System\Commands;

use System\ORM\ORM;

class Model extends Command
{

    public function run($table = null) {

        $orm = new ORM();
        if($table) {
            $list_table = [$table];
        } else {
            $list_table = $orm->listTable();
        }

        foreach($list_table as $table) {

            //Create model configuration
            $this->createModelConfigurationByTable($orm, $table);

            $model_file = base_path("app/Configs/Models/".$table.".php");
            $model_name = convert_snake_to_CamelCase(str_replace(".php","",basename($model_file)), true);
            $model = include $model_file;
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

                    if(isset($column['join_model'])) {
                    $todo .= "\t/** @return ".$column['join_model']." */\n";
                    }

                    $todo .= "\tpublic function get".convert_snake_to_CamelCase($column['name'], true)."() {\n";
                    if(isset($column['join_model'])) {
                    $todo .= "\t\t".'return '.$column['join_model'].'::findById($this->'.$column['name'].');'."\n";
                    } else {
                    $todo .= "\t\t".'return $this->'.$column['name'].';'."\n";
                    }
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


    public function createModelConfigurationByTable(ORM $orm, $table) {
        $list_column = $orm->listColumn($table);
        $columns = [];
        foreach($list_column as $column) {
            if(substr(strtolower($column), -3, 3) == "_id") {
                $join_model = substr($column,0, strpos($column,"_id"));
                $join_model = convert_snake_to_CamelCase($join_model, true);
                $columns[] = [
                    'name'=>$column,
                    'column'=>$column,
                    'join_model'=> $join_model
                ];
            } else {
                $columns[] = [
                    'name'=>$column,
                    'column'=>$column
                ];
            }
        }

        $table_camel_case = convert_snake_to_CamelCase($table, true);
        $result = [
            'table'=>$table,
            'primary_key'=> $orm->findPrimaryKey($table),
            'columns'=>$columns
        ];

        file_put_contents(base_path("app/Configs/Models/".$table_camel_case.".php"), '<?php return '.var_min_export($result, true).';');
    }

}