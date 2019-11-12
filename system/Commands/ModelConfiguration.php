<?php

namespace System\Commands;

use System\ORM\ORM;

class ModelConfiguration extends Command
{

    public function run() {

        $orm = new ORM();
        $list_table = $orm->listTable();
        $result = [];
        foreach($list_table as $table) {
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
            $result[$table_camel_case] = [
              'table'=>$table,
              'primary_key'=> $orm->findPrimaryKey($table),
              'columns'=>$columns
            ];
        }

        file_put_contents(base_path("app/Configs/model.php"), '<?php return '.var_min_export($result, true).';');

        print "Model configuration has been generated!. You can still customizing it at app/Configs/model.php manually.";
    }

}