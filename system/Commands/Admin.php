<?php

namespace System\Commands;

use System\ORM\ORM;

class Admin extends Command
{

    public function run($table_name) {

        $orm = new ORM();

        if($orm->hasTable($table_name)) {
            if(!file_exists(base_path('vue-template/'.$table_name))) {
                mkdir(base_path('vue-template/'.$table_name));
            }

            // Create model
            (new Model())->run($table_name);

            // Create API controller
            $this->createAPI($table_name);

            // Create index file js
            $this->createIndexFile($table_name);

            // Create form file js
            $this->createFormFile($table_name);

            // Create vue router
            $this->createVueRouter($table_name);

            // Create navbar menu
            $this->createNavbarMenu($table_name);

            print "Create `".$table_name."` vue module has been completed!";
        } else {
            print "Table ".$table_name." is not found!";
        }
    }

    private function createNavbarMenu($table_name)
    {
        $module = ucwords(str_replace("_"," ",$table_name));
        $template = file_get_contents(base_path("app/Modules/Admin/Views/index.php"));
        $pattern = "<!-- User Custom Navbar Menu After This -->";
        $replace_str = "\t\t\t\t\t<li class=\"nav-item\"><a class=\"nav-link\" href=\"javascript:;\" @click=\"\$router.push('/$table_name')\">$module</a></li>";
        $template = str_replace($replace_str,"",$template);
        $template = str_replace($pattern,$pattern."\n".$replace_str, $template);

        $template = preg_replace("/(\r?\n){2,}/", "\n", $template);

        file_put_contents(base_path("app/Modules/Admin/Views/index.php"), $template);
    }

    private function createVueRouter($table_name)
    {
        $template = file_get_contents(base_path("vue-template/router.js"));

        // Insert import router
        $pattern = "/* User Custom Import Router */";
        $import1 = "import ".$table_name." from \"./$table_name/index.vue.js\"\n";
        $template = str_replace($import1,"",$template);
        $import2 = "import ".$table_name."_form from \"./$table_name/form.vue.js\"";
        $template = str_replace($import2,"",$template);
        $template = str_replace($pattern,$pattern."\n".$import1.$import2, $template);

        // Router
        $pattern = "/* User Custom Router */";
        $replace_str = "{path: '/$table_name', component: $table_name},\n";
        $replace_str .= "{path: '/$table_name/add', component: ".$table_name."_form},\n";
        $replace_str .= "{path: '/$table_name/edit/:id', component: ".$table_name."_form},";
        $template = str_replace($replace_str,"",$template);
        $template = str_replace($pattern, $pattern."\n".$replace_str, $template);

        // Remove multiple new line
        $template = preg_replace("/(\r?\n){2,}/", "\n", $template);

        file_put_contents(base_path("vue-template/router.js"), $template);
    }

    private function createAPI($table_name)
    {
        $orm = new ORM();
        $index_template = file_get_contents(base_path("system/Commands/AdminTemplate/Controller.php.stub"));
        $columns = $orm->listColumn($table_name);
        $pk = $orm->findPrimaryKey($table_name);
        $module = ucwords(str_replace("_"," ",$table_name));

        // Replace module
        $index_template = str_replace("{module}", $module, $index_template);

        // Replace permalink
        $index_template = str_replace("{permalink}", $table_name, $index_template);

        // Replace primary key
        $index_template = str_replace("{primary_key}", $pk, $index_template);

        // Replace controller
        $controller_name = "Api".convert_snake_to_CamelCase($table_name, true)."Controller";
        $index_template = str_replace("{controller}", $controller_name, $index_template);

        // Replace model
        $model = convert_snake_to_CamelCase($table_name, true);
        $index_template = str_replace("{model}", $model, $index_template);

        // Replace array column
        $index_template = str_replace("{array_columns}", var_min_export($columns, true), $index_template);

        // Replace array no pk column
        $columns_no_pk = [];
        foreach($columns as $column) {
            if(!in_array($column,[$pk,'created_at','updated_at'])) {
                $columns_no_pk[] = $column;
            }
        }
        $index_template = str_replace("{array_no_pk_columns}", var_min_export($columns_no_pk, true), $index_template);

        // Replace model setter
        $model_setter = "";
        foreach($columns as $column) {
            if(!in_array($column,[$pk,'created_at','updated_at'])) {
                $model_setter .= "\$row->set".convert_snake_to_CamelCase($column, true)."(request_string(\"$column\"));\n\t\t\t\t";
            }
        }
        $index_template = str_replace("{model_setter}", $model_setter, $index_template);

        // Replace data model assigned
        $data_model_assign = "";
        foreach($columns as $column) {
            $data_model_assign .= "\$data['$column'] = \$row->get".convert_snake_to_CamelCase($column,true)."();\n\t\t\t\t";
        }
        $index_template = str_replace("{data_model_assign}", $data_model_assign, $index_template);

        // File put controller
        file_put_contents(base_path("app/Modules/AdminAPI/Controllers/".$controller_name.".php"), $index_template);

        // Update router
        (new Route())->run();
    }

    private function createFormFile($table_name)
    {
        $orm = new ORM();
        $index_template = file_get_contents(base_path("system/Commands/AdminTemplate/form.vue.js"));
        $columns = $orm->listColumn($table_name);
        $pk = $orm->findPrimaryKey($table_name);

        // Replace module
        $module_name = ucwords(str_replace("_"," ", $table_name));
        $index_template = str_replace("{module}", $module_name, $index_template);

        // Replace primary key
        $index_template = str_replace("{primary_key}", $pk, $index_template);

        // Replace permalink
        $permalink = $table_name;
        $index_template = str_replace("{permalink}", $permalink, $index_template);

        // Replace form group
        $form_group = "";
        foreach($columns as $column) {
            if(!in_array($column,[$pk,'created_at','updated_at'])) {
                $label = ucwords(str_replace("_"," ", $column));
                if(preg_match("/description|content/i", $column)) {
                    $form_group .= "
                <div class='form-group'>
                    <label>$label</label>
                    <textarea name='$column' class='form-control' v-model='$column' required rows='6'></textarea>                    
                </div>
            ";
                } elseif (preg_match("/price|number|amount|harga|jumlah|nilai/i", $column)) {
                    $form_group .= "
                <div class='form-group'>
                    <label>$label</label>
                    <input type='number' name='$column' @keyup.enter='submitForm' v-model='$column' required class='form-control'>                    
                </div>
            ";
                } else {
                    $form_group .= "
                <div class='form-group'>
                    <label>$label</label>
                    <input type='text' name='$column' @keyup.enter='submitForm' v-model='$column' required class='form-control'>
                </div>
            ";
                }

            }
        }
        $index_template = str_replace("{form_group}", $form_group, $index_template);

        // Replace data return
        $data_return = "";
        foreach($columns as $column) {
            $data_return .= "$column:\"\",\n\t\t\t";
        }
        $index_template = str_replace("{data_return}", $data_return, $index_template);

        // Replace clear form
        $data_clear_form = "";
        foreach($columns as $column) {
            if($column != $pk) {
                $data_clear_form .= "this.".$column." = \"\"\n\t\t\t";
            }
        }
        $index_template = str_replace("{clear_form}", $data_clear_form, $index_template);

        // Replace data load form
        $data_load_form = "";
        foreach($columns as $column) {
            if($column != $pk) {
                $data_load_form .= "this.".$column." = resp.data.data.$column\n\t\t\t\t\t";
            }
        }
        $index_template = str_replace("{data_load_form}", $data_load_form, $index_template);

        // Replace data submit form
        $data_submit_form = "";
        foreach($columns as $column) {
            $data_submit_form .= $column.": this.$column,\n\t\t\t\t";
        }
        $index_template = str_replace("{data_submit_form}", $data_submit_form, $index_template);

        // Put form file
        file_put_contents(base_path("vue-template/".$table_name."/form.vue.js"), $index_template);
    }

    private function createIndexFile($table_name)
    {
        $orm = new ORM();
        $pk = $orm->findPrimaryKey($table_name);

        $index_template = file_get_contents(base_path("system/Commands/AdminTemplate/index.vue.js"));

        // Replace permalink
        $index_template = str_replace("{permalink}", $table_name, $index_template);

        // Replace PK
        $index_template = str_replace("{primary_key}", $pk, $index_template);

        // Replace module
        $module_name = ucwords(str_replace("_"," ", $table_name));
        $index_template = str_replace("{module}", $module_name, $index_template);

        // Replace thead columns
        $thead = "";
        $columns = $orm->listColumn($table_name);
        foreach($columns as $column) {
            $thead .= "<th>".ucwords(str_replace("_"," ",$column))."</th>";
        }
        $index_template = str_replace("{thead_columns}", $thead, $index_template);
        $index_template = str_replace("{action_target_idx}", count($columns), $index_template);

        // Replace json columns
        $json_columns = [];
        foreach($columns as $column) {
            $json_columns[] = ['data'=>$column];
        }
        // add action
        $json_columns[] = ['data'=>$pk];
        $index_template = str_replace("{json_columns}", json_encode($json_columns), $index_template);

        // Put file index
        file_put_contents(base_path("vue-template/".$table_name."/index.vue.js"), $index_template);
    }

}