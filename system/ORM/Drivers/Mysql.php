<?php

namespace System\ORM\Drivers;

use Exception;

class Mysql
{
    private $connection;
    private $table;
    private $select;
    private $where;
    private $limit;
    private $offset;
    private $order_by;
    private $group_by;
    private $having;
    private $join;
    private $join_type;
    private $last_query;

    public function __construct(\PDO $connection, $table, $select, $join, $join_type, $where, $limit, $offset, $order_by, $group_by, $having)
    {
        $this->connection = $connection;
        $this->table = $table;
        $this->select = $select;
        $this->where = $where;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->order_by = $order_by;
        $this->group_by = $group_by;
        $this->having = $having;
        $this->join = $join;
        $this->join_type = $join_type;
    }

    public function findPrimaryKey($table) {
        if($pk = get_singleton("findPrimaryKey_".$table)) {
            return $pk;
        } else {
            $query = $this->connection->query("DESCRIBE ".$table);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $query->fetchAll();
            foreach($result as $row) {
                if($row['Key'] == 'PRI') {
                    put_singleton("findPrimaryKey_".$table,$row['Field']);
                    return $row['Field'];
                }
            }
            return null;
        }
    }

    /**
     * @param $table
     * @param $column
     * @return bool
     */
    public function hasColumn($table, $column) {
        $columns = $this->listColumn($table);
        return in_array($column, $columns);
    }

    public function hasTable($table) {

        // Try a select statement against the table
        // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
        try {
            $table = filter_var($table,FILTER_SANITIZE_STRING);
            $result = $this->connection->query("SELECT 1 FROM `".$table."` LIMIT 1");
        } catch (Exception $e) {
            // We got an exception == table not found
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }

    public function update($array) {
        $sets = [];
        foreach($array as $key=>$value) {
            $sets[] = $key."='".$value."'";
        }

        $where_sql = (isset($this->where))?"WHERE ".implode(" AND ",$this->where):"";
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
            $where_sql = "WHERE ".$this->table.".".$this->findPrimaryKey($this->table)." = '".$id."'";
        }

        if(isset($this->where)) {
            $where_sql = ($where_sql)?$where_sql." AND ".implode(" AND ",$this->where):"WHERE ".implode(" AND ",$this->where);
        }

        $count = $this->connection->exec("DELETE FROM ".$this->table." ".$where_sql);
        return $count;
    }

    public function find($id = null) {
        $where_sql = "";

        if($id) {
            $where_sql = "WHERE ".$this->table.".".$this->findPrimaryKey($this->table)." = '".$id."'";
        }

        if(isset($this->where)) {
            $where_sql = ($where_sql)?$where_sql." AND ".implode(" AND ",$this->where):"WHERE ".implode(" AND ",$this->where);
        }

        $order_by_sql = (isset($this->order_by))?"ORDER BY ".$this->order_by:"";

        $join_sql = "";
        if($this->join) {
            foreach($this->join as $i=>$join) {
                $join_sql .= $this->join_type[$i]." ".$join." ";
            }
        }

        $this->last_query = "SELECT ".$this->select." FROM `".$this->table."` ".$join_sql." ".$where_sql." ".$order_by_sql." LIMIT 1";
        $stmt = $this->connection->query($this->last_query);
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
        $where_sql = (isset($this->where))?"WHERE ".implode(" AND ",$this->where):"";
        $order_by_sql = (isset($this->order_by))?"ORDER BY ".$this->order_by:"";
        $limit_sql = (isset($this->limit))?"LIMIT ".$this->limit:"";
        $limit_sql .= (isset($this->offset))?" OFFSET ".$this->offset:"";
        $this->last_query = "SELECT ".$this->select." FROM `".$this->table."` ".$join_sql." ".$where_sql." ".$order_by_sql." ".$limit_sql;
        $stmt = $this->connection->query($this->last_query);
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
        $where_sql = (isset($this->where))?"WHERE ".implode(" AND ",$this->where):"";
        $order_by_sql = (isset($this->order_by))?"ORDER BY ".$this->order_by:"";
        $limit_sql = (isset($this->limit))?"LIMIT ".$this->limit:"";
        $limit_sql .= (isset($this->offset))?" OFFSET ".$this->offset:"";

        $this->last_query = "SELECT ".$this->select." FROM `".$this->table."` ".$join_sql." ".$where_sql." ".$order_by_sql." ".$limit_sql;
        $stmt = $this->connection->query($this->last_query);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $data = [];
        $data['data'] = $stmt->fetchAll();

        // Get Total
        $stmt = $this->connection->query("SELECT COUNT(*) as total_records FROM `".$this->table."` ".$join_sql." ".$where_sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $data['total'] = $stmt->fetch()['total_records'];
        return $data;
    }

    public function getLastQuery()
    {
        return $this->last_query;
    }

    public function count() {
        $join_sql = "";

        if($this->join) {
            foreach($this->join as $i=>$join) {
                $join_sql .= $this->join_type[$i]." ".$join." ";
            }
        }

        $where_sql = (isset($this->where))?"WHERE ".implode(" AND ",$this->where):"";
        $this->last_query = "SELECT count(*) as total_records FROM `".$this->table."` ".$join_sql." ".$where_sql;
        $stmt = $this->connection->query($this->last_query);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt->fetch()['total_records'];
    }

    public function listColumn($table) {
        $columns = [];
        $rs = $this->connection->query('SELECT * FROM '.$table.' LIMIT 0');
        for ($i = 0; $i < $rs->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    public function listTable()
    {
        $query = $this->connection->query('SHOW TABLES');
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

}