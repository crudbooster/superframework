<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/12/2019
 * Time: 3:11 PM
 */

namespace App\UtilModel;


use App\UtilORM\ORM;

class Model
{

    private static function modelSetter($model, $row) {
        foreach($row as $column => $value) {
            $model->$column = $value;
        }
        return $model;
    }

    private static function primaryKey() {
        return (new static())->primaryKey;
    }

    private static function tableName() {
        return (new static())->table;
    }

    /**
     * @throws \ReflectionException
     */
    private static function isSoftDelete() {
        $class = new \ReflectionClass(static::class);
        if($class->hasProperty("deleted_at")) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws \ReflectionException
     */
    private static function columns() {
        $class = new \ReflectionClass(static::class);
        $result = [];
        foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $result[] = $property->getName();
        }
        return $result;
    }

    /**
     * @param $limit
     * @param $offset
     * @param callable|null $query
     * @return null
     * @throws \Exception
     */
    private static function queryAll($limit, $offset, callable $query = null) {
        $data = db(static::tableName());
        foreach(static::columns() as $column) {
            $data->addSelect(static::tableName().".".$column);
        }

        if(isset($query)) {
            $data = call_user_func($query, $data);
        }

        $result = $data->all($limit, $offset);

        return $result;
    }

    /**
     * @param array $data_array
     * @return mixed
     */
    public static function loadArray(array $data_array) {
        return static::modelSetter(new static(), $data_array);
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

        if($id) {
            if($last_data = get_singleton(basename(get_called_class()).'_findById_'.$id)) {
                return $last_data;
            } else {
                // Get record
                $row = db(static::tableName())->find($id);
                if($row) {
                    $data = static::modelSetter(new static(), $row);
                    put_singleton(basename(get_called_class()).'_findById_'.$id, $data);
                    return $data;
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }

    }

    /**
     * @param $column
     * @param $value
     * @return static
     * @throws \Exception
     */
    public static function findBy($column, $value) {
        if($column && $value) {
            if($last_data = get_singleton(basename(get_called_class()).'_findBy_'.$column.'_'.$value)) {
                return $last_data;
            } else {
                // Get record
                $row = db(static::tableName())->where($column." = '".$value."'")->find();
                if ($row) {
                    $data = static::modelSetter(new static(), $row);
                    put_singleton(basename(get_called_class()).'_findBy_'.$column.'_'.$value, $data);
                    return $data;
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }
    }


    /**
     * @return $this
     * @throws \Exception
     */
    public function save() {
        $data_array = [];
        foreach(static::columns() as $column) {
            if(static::primaryKey() != $column) {
                $value = $this->$column;
                if(isset($value)) {
                    $data_array[ $column ] = $this->$column;
                }
            }
        }

        $id = $this->{static::primaryKey()};
        if($id) {
            if((new ORM())->hasColumn(static::tableName(), 'updated_at')) {
                $data_array['updated_at'] = date('Y-m-d H:i:s');
            }
            db(static::tableName())->where(static::primaryKey()." = '".$id."'")->update($data_array);
        } else {
            if((new ORM())->hasColumn(static::tableName(), 'created_at')) {
                $data_array['created_at'] = date('Y-m-d H:i:s');
            }
            $id = db(static::tableName())->insert($data_array);
        }

        $this->{static::primaryKey()} = $id;
        return $this;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public static function delete($id) {
        if(static::isSoftDelete()) {
            db(static::tableName())->where(static::primaryKey()." = '".$id."'")->update(["deleted_at"=>date("Y-m-d H:i:s")]);
        } else {
            db(static::tableName())->delete($id);
        }
    }

    /**
     * @param $where_raw
     * @throws \Exception
     */
    public static function deleteWhere($where_raw) {
        if(static::isSoftDelete()) {
            db(static::tableName())->where($where_raw)->update(["deleted_at"=>date("Y-m-d H:i:s")]);
        } else {
            db(static::tableName())->where($where_raw)->delete();
        }
    }

}