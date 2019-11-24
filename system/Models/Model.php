<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/12/2019
 * Time: 3:11 PM
 */

namespace System\Models;


use System\ORM\ORM;

class Model
{
    private $config;

    public function __construct()
    {
        $this->config = include base_path("app/Configs/model.php");
    }

    public function getInstanceConfig() {
        return $this->config;
    }

    private static function modelSetter($model, $columns, $row) {
        foreach($columns as $column) {
            $method_name = "set".convert_snake_to_CamelCase($column['name'], true);
            $value = $row[ $column['column'] ];

            if(isset($column['join_model'])) {
              $join_model = $column['join_model'];
              $value = ("\App\Models\\".$join_model)::findById($value);
            }

            $model->$method_name($value);
        }
        return $model;
    }

    private static function getConfig($class_name = null) {
        $class_model_name = ($class_name)?:get_called_class();
        $class_array = explode("\\",$class_model_name);
        $class_model_name = end($class_array);
        return (new self)->getInstanceConfig()[$class_model_name];
    }

    public static function getPrimaryKey() {
        $config = static::getConfig();
        return $config['primary_key'];
    }

    public static function getTableName() {
        $config = static::getConfig();
        return $config['table'];
    }

    /**
     * @param $limit
     * @param $offset
     * @param callable|null $query
     * @return null
     * @throws \Exception
     */
    private static function queryAll($limit, $offset, callable $query = null) {
        $config = static::getConfig();
        $data = DB($config['table']);
        foreach($config['columns'] as $i=>$column) {
            if(isset($column['join_model'])) {
                $join_config = static::getConfig($column['join_model']);
                $data->join($join_config['table']." as ".$join_config['table']." on ".$join_config['table'].".".$join_config['primary_key']." = ".$column['column']);
                foreach($join_config['columns'] as $join_column) {
                    $data->addSelect($join_config['table'].".".$join_column['column']." as ".$join_config['table']."___".$join_column['column']);
                }
            } else {
                $data->addSelect($config['table'].".".$column['column']);
            }
        }

        if(isset($query)) {
            $data = call_user_func($query, $data);
        }

        $result = $data->all($limit, $offset);

        foreach($result as $i=>$row) {
            foreach($row as $row_column => $row_value) {
                if($column_arr = explode("___", $row_column)) {
                    if(isset($column_arr[1])) {
                        $result[$i][$column_arr[0]][$column_arr[1]] = $row_value;
                        unset($result[$i][$row_column]);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param array $data_array
     * @return mixed
     */
    public static function loadArray(array $data_array) {
        $config = static::getConfig();
        return static::modelSetter(new static(), $config['columns'], $data_array);
    }

    /**
     * @param $limit
     * @param $offset
     * @return null
     * @throws \Exception
     */
    public static function findAll($limit = null, $offset=null) {
        return static::queryAll($limit, $offset);
    }

    /**
     * @param $column
     * @param $value
     * @param null $limit
     * @param null $offset
     * @return null
     * @throws \Exception
     */
    public static function findAllBy($column, $value, $limit = null, $offset = null) {
        return static::queryAll($limit, $offset, function(ORM $query) use ($column,$value) {
            return $query->where($column." = '".$value."'");
        });
    }

    /**
     * @param $id
     * @return null|$this
     * @throws \Exception
     */
    public static function findById($id) {

        if($last_data = get_singleton(basename(get_called_class()).'_findById_'.$id)) {
            return $last_data;
        } else {
            $config = static::getConfig();
            // Get record
            $row = DB($config['table'])->find($id);
            if($row) {
                $data = static::modelSetter(new static(), $config['columns'], $row);
                put_singleton(basename(get_called_class()).'_findById_'.$id, $data);
                return $data;
            } else {
                return null;
            }
        }
    }

    /**
     * @param $column
     * @param $value
     * @return static
     * @throws \Exception
     */
    public static function findBy($column, $value) {
        if($last_data = get_singleton(basename(get_called_class()).'_findBy_'.$column.'_'.$value)) {
            return $last_data;
        } else {
            $config = static::getConfig();
            // Get record
            $row = DB($config['table'])->where($column." = '".$value."'")->find();
            if ($row) {
                $data = static::modelSetter(new static(), $config['columns'], $row);
                put_singleton(basename(get_called_class()).'_findBy_'.$column.'_'.$value, $data);
                return $data;
            } else {
                return null;
            }
        }
    }


    /**
     * @return $this
     * @throws \Exception
     */
    public function save() {
        $config = static::getConfig();
        $data_array = [];
        foreach($config['columns'] as $column) {
            $method_name = "get".convert_snake_to_CamelCase($column['name'], true);
            if($config['primary_key'] != $column['column']) {
                if($this->$method_name()) {
                    if(is_object($this->$method_name())) {
                        $method_pk = "get".convert_snake_to_CamelCase($this->$method_name()->getPrimaryKey(), true);
                        $data_array[ $column['column'] ] = $this->$method_name()->$method_pk();
                    } else {
                        $data_array[ $column['column'] ] = $this->$method_name();
                    }
                }
            }
        }

        $method_get_pk = "get".convert_snake_to_CamelCase($config['primary_key'], true);
        if($last_id = $this->$method_get_pk()) {
            DB($config['table'])->where($config['primary_key']." = '".$last_id."'")->update($data_array);
        } else {
            $last_id = DB($config['table'])->insert($data_array);
        }

        $method_set_pk = "set".convert_snake_to_CamelCase($config['primary_key'], true);
        $this->$method_set_pk($last_id);
        return $this;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete($id) {
        $config = static::getConfig();
        DB($config['table'])->delete($id);
    }

    /**
     * @param array $param
     * @throws \Exception
     */
    public function deleteBy(array $param) {
        $config = static::getConfig();
        DB($config['table'])->delete($param);
    }

}