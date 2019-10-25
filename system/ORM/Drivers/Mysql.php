<?php

namespace System\ORM\Drivers;

class Mysql
{
    private $connection;
    private $table;
    private $select;
    private $primary_key;
    private $where;
    private $limit;
    private $offset;
    private $order_by;
    private $group_by;
    private $having;
    private $join;
    private $join_type;

    public function __construct($connection, $table, $select, $primary_key, $join, $join_type, $where, $limit, $offset, $order_by, $group_by, $having)
    {
        $this->connection = $connection;
        $this->table = $table;
        $this->select = $select;
        $this->primary_key = $primary_key;
        $this->where = $where;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->order_by = $order_by;
        $this->group_by = $group_by;
        $this->having = $having;
        $this->join = $join;
        $this->join_type = $join_type;
    }

    public function update($array) {
        $sets = [];
        foreach($array as $key=>$value) {
            $sets[] = $key."='".$value."'";
        }

        $where_sql = (isset($this->where))?"WHERE ".$this->where:"";
        $count = $this->connection->exec("UPDATE `".$this->table."` SET ".implode(",",$sets)." ".$where_sql);
        return $count;
    }

    public function insert($array) {
        $fields = array_keys($array);
        $values = array_values($array);
        $this->connection->exec("INSERT INTO `".$this->table."` (".implode(",", $fields).") VALUES ('".implode("','",$values)."')");
        return $this->connection->lastInsertId($this->table);
    }

    public function delete($id = null) {
        $where_sql = "";

        if($id) {
            $where_sql = "WHERE ".$this->table.".".$this->primary_key." = '".$id."'";
        }

        if(isset($this->where)) {
            $where_sql = ($where_sql)?$where_sql." ".$this->where:"WHERE ".$this->where;
        }

        $count = $this->connection->exec("DELETE FROM ".$this->table." WHERE ".$where_sql);
        return $count;
    }

    public function find($id = null) {
        $where_sql = "";

        if($id) {
            $where_sql = "WHERE ".$this->table.".".$this->primary_key." = '".$id."'";
        }

        if(isset($this->where)) {
            $where_sql = ($where_sql)?$where_sql." ".$this->where:"WHERE ".$this->where;
        }

        $order_by_sql = (isset($this->order_by))?"ORDER BY ".$this->order_by:"";

        $join_sql = "";
        if($this->join) {
            foreach($this->join as $i=>$join) {
                $join_sql .= $this->join_type[$i]." ".$join." ";
            }
        }

        $stmt = $this->connection->query("SELECT ".$this->select." FROM `".$this->table."` ".$join_sql." ".$where_sql." ".$order_by_sql." LIMIT 1");
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    public function all() {

        $join_sql = "";
        if($this->join) {
            foreach($this->join as $i=>$join) {
                $join_sql .= $this->join_type[$i]." ".$join." ";
            }
        }
        $where_sql = (isset($this->where))?"WHERE ".$this->where:"";
        $order_by_sql = (isset($this->order_by))?"ORDER BY ".$this->order_by:"";
        $limit_sql = (isset($this->limit))?"LIMIT ".$this->limit:"";
        $limit_sql .= (isset($this->offset))?" OFFSET ".$this->offset:"";
        $stmt = $this->connection->query("SELECT ".$this->select." FROM `".$this->table."` ".$join_sql." ".$where_sql." ".$order_by_sql." ".$limit_sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    public function paginate() {

        $page = request_int('page', 1);
        $this->offset = ($page - 1) * $this->limit;

        $join_sql = "";
        if($this->join) {
            foreach($this->join as $i=>$join) {
                $join_sql .= $this->join_type[$i]." ".$join." ";
            }
        }
        $where_sql = (isset($this->where))?"WHERE ".$this->where:"";
        $order_by_sql = (isset($this->order_by))?"ORDER BY ".$this->order_by:"";
        $limit_sql = (isset($this->limit))?"LIMIT ".$this->limit:"";
        $limit_sql .= (isset($this->offset))?" OFFSET ".$this->offset:"";

        $stmt = $this->connection->query("SELECT ".$this->select." FROM `".$this->table."` ".$join_sql." ".$where_sql." ".$order_by_sql." ".$limit_sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $data = [];
        $data['data'] = $stmt->fetchAll();

        // Get Total
        $stmt = $this->connection->query("SELECT COUNT(*) as total_records FROM `".$this->table."` ".$join_sql." ".$where_sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $data['total'] = $stmt->fetch()['total_records'];
        return $data;
    }
}