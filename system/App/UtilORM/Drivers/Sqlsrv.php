<?php

namespace System\App\UtilORM\Drivers;

use Exception;

class Sqlsrv
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
            $query = $this->connection->query("select COLUMN_NAME 
            from information_schema.KEY_COLUMN_USAGE 
            where CONSTRAINT_NAME='PRIMARY' AND TABLE_NAME='$table' 
            AND TABLE_SCHEMA='".config("database.database")."'");
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $query->fetch();
            return $result['COLUMN_NAME'] ?: "id";
        }
    }

    public function orderByRandom() {
        return "NEWID()";
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
        $tables = $this->listTable();
        return in_array($table, $tables);
    }

    public function update($array) {
        $sets = [];
        foreach($array as $key=>$value) {
            $sets[] = $key."='".$value."'";
        }

        $where_sql = (isset($this->where))?"WHERE ".implode(" AND ",$this->where):"";
        $count = $this->connection->exec("UPDATE ".$this->table." SET ".implode(",",$sets)." ".$where_sql);
        return $count;
    }

    public function insert($array) {
        $fields = array_keys($array);
        $values = array_values($array);
        $insertQuery = "INSERT INTO ".$this->table." (".implode(",", $fields).") VALUES ('".implode("','",$values)."'); select SCOPE_IDENTITY() AS 'Identity';";
        $this->connection->exec($insertQuery);

        $pk = $this->findPrimaryKey($this->table);
        $lastId = $this->connection->query("select TOP 1 ".$pk." from ".$this->table." order by ".$pk." desc");
        $lastId = $lastId->fetchColumn(0);
        return $lastId;
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

        if(isset($this->where) && count($this->where)) {
            $where_sql = ($where_sql)?$where_sql." AND ".implode(" AND ",$this->where):"WHERE ".implode(" AND ",$this->where);
        }

        $order_by_sql = (isset($this->order_by))?"ORDER BY ".$this->order_by:"";

        $join_sql = "";
        if($this->join) {
            foreach($this->join as $i=>$join) {
                $join_sql .= $this->join_type[$i]." ".$join." ";
            }
        }

        $this->last_query = "SELECT TOP 1 ".$this->select." FROM ".$this->table." ".$join_sql." ".$where_sql." ".$order_by_sql;
        logging("LastQuery = ".$this->last_query);
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
        $limit_sql = (isset($this->limit))?"TOP ".$this->limit:"";
        $group_by_sql = (isset($this->group_by)) ? "GROUP BY ".$this->group_by : "";
        $offset_sql = (isset($this->offset))?" OFFSET ".$this->offset." ROWS":"";
        $this->last_query = "SELECT ".$limit_sql." ".$this->select." FROM ".$this->table." ".$join_sql." ".$where_sql." ".$group_by_sql." ".$order_by_sql." ".$offset_sql;
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
        $limit_sql = (isset($this->limit))?"TOP ".$this->limit:"";
        $offset_sql = (isset($this->offset))?" OFFSET ".$this->offset." ROWS":"";

        $this->last_query = "SELECT ".$limit_sql." ".$this->select." FROM ".$this->table." ".$join_sql." ".$where_sql." ".$order_by_sql." ".$offset_sql;
        $stmt = $this->connection->query($this->last_query);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $data = [];
        $data['data'] = $stmt->fetchAll();

        // Get Total
        $stmt = $this->connection->query("SELECT COUNT(*) as total_records FROM ".$this->table." ".$join_sql." ".$where_sql);
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
        $this->last_query = "SELECT count(*) as total_records FROM ".$this->table." ".$join_sql." ".$where_sql;
        $stmt = $this->connection->query($this->last_query);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt->fetch()['total_records'];
    }

    public function sum($field) {
        $join_sql = "";

        if($this->join) {
            foreach($this->join as $i=>$join) {
                $join_sql .= $this->join_type[$i]." ".$join." ";
            }
        }

        $where_sql = (isset($this->where))?"WHERE ".implode(" AND ",$this->where):"";
        $this->last_query = "SELECT sum($field) as total_records FROM ".$this->table." ".$join_sql." ".$where_sql;
        $stmt = $this->connection->query($this->last_query);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt->fetch()['total_records'];
    }

    public function listColumn($table) {
        $columns = [];
        $rs = $this->connection->query('SELECT TOP 0 * FROM '.$table);
        for ($i = 0; $i < $rs->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    public function listTable()
    {
        $query = $this->connection->query("SELECT TABLE_NAME 
        FROM ".config("database.database").".INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_TYPE = 'BASE TABLE'");
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

}