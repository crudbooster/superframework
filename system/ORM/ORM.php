<?php

namespace System\ORM;

use System\ORM\Drivers\Mysql;

class ORM
{
    private $config;
    private $connection;
    private $table;
    private $primary_key = "id";
    private $select = "*";
    private $where;
    private $join;
    private $join_type;
    private $limit;
    private $offset;
    private $order_by;
    private $group_by;
    private $having;

    public function __construct()
    {
        $this->config = include getcwd()."/app/configs/database.php";
    }

    private function createConnection() {
        if(!$this->connection) {
            try {
                $this->connection = new \PDO($this->config['driver'].":host=".$this->config['host'].";dbname=".$this->config['database'], $this->config['username'], $this->config['password'], array(
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ERRMODE_EXCEPTION => true));
            } catch (\PDOException $e) {
                logging($e);
                abort(500);
                exit;
            }
        }
    }

    /**
     * @param string $table
     * @return $this
     */
    public function db($table) {
        $this->table = $table;
        return $this;
    }

    /**
     * @return $this
     */
    public function select() {
        $arguments = func_get_args();
        $this->select = implode(",",$arguments);
        return $this;
    }

    /**
     * @param string $field_name
     * @return $this
     */
    public function addSelect($field_name) {
        if($this->select == "*") {
            $this->select = $field_name;
        } else {
            $this->select .= ",".$field_name;
        }
        return $this;
    }


    /**
     * @param string $join SQL Join Syntax
     * @param string $join_type SQL Join Type
     * @return $this
     */
    public function join($join, $join_type = "INNER JOIN") {
        $this->join[] = $join;
        $this->join_type[] = $join_type;
        return $this;
    }

    /**
     * @param string $table_name
     * @return $this
     */
    public function with($table_name) {
        $this->join($table_name." on ".$table_name.".id = ".$table_name."_id");
        return $this;
    }

    /**
     * @param string $where_query SQL where syntax
     * @return $this
     */
    public function where($where_query) {
        $this->where = $where_query;
        return $this;
    }

    /**
     * @param string $order_by SQL Order By Syntax
     * @return $this
     */
    public function orderBy($order_by) {
        $this->order_by = $order_by;
        return $this;
    }

    /**
     * @param string $group_by SQL Group By Syntax
     * @return $this
     */
    public function groupBy($group_by) {
        $this->group_by = $group_by;
        return $this;
    }

    /**
     * @param string $having SQL Having Syntax
     * @return $this
     */
    public function having($having) {
        $this->having = $having;
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function update($array) {
        $this->createConnection();
        if($this->config['driver'] == "mysql") {
            return (new Mysql($this->connection, $this->table, $this->select, $this->primary_key, $this->join, $this->join_type, $this->where, $this->limit, $this->offset, $this->order_by, $this->group_by, $this->having))->update($array);
        }

        return null;
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function insert($array) {
        $this->createConnection();
        if($this->config['driver'] == "mysql") {
            return (new Mysql($this->connection, $this->table, $this->select, $this->primary_key, $this->join, $this->join_type, $this->where, $this->limit, $this->offset, $this->order_by, $this->group_by, $this->having))->insert($array);
        }

        return null;
    }

    /**
     * @param int|array|null $id
     * @return mixed
     */
    public function delete($id = null) {
        $this->createConnection();
        if($this->config['driver'] == "mysql") {
            return (new Mysql($this->connection, $this->table, $this->select, $this->primary_key, $this->join, $this->join_type, $this->where, $this->limit, $this->offset, $this->order_by, $this->group_by, $this->having))->delete($id);
        }

        return null;
    }

    /**
     * @param null|int $id
     * @return mixed|array
     */
    public function find($id = null) {
        $this->createConnection();
        if($this->config['driver'] == "mysql") {
            return (new Mysql($this->connection, $this->table, $this->select, $this->primary_key, $this->join, $this->join_type, $this->where, $this->limit, $this->offset, $this->order_by, $this->group_by, $this->having))->find($id);
        }

        return null;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return null
     */
    public function all($limit = null, $offset = 0) {
        $this->createConnection();

        if($limit) $this->limit = $limit;
        if($offset) $this->offset = $offset;

        if($this->config['driver'] == "mysql") {
            return (new Mysql($this->connection, $this->table, $this->select, $this->primary_key, $this->join, $this->join_type, $this->where, $this->limit, $this->offset, $this->order_by, $this->group_by, $this->having))->all();
        }

        return null;
    }
}