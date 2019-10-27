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
                $this->connection = new \PDO($this->config['driver'].":host=".$this->config['host'].";dbname=".$this->config['database'], $this->config['username'], $this->config['password']);
                $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            } catch (\Exception $e) {
                logging($e);
                abort(500);
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
     * @return ORM $this
     */
    public function where($where_query) {
        $this->where[] = $where_query;
        return $this;
    }


    /**
     * @param $var
     * @param $where_query
     * @return ORM $this
     */
    public function whereIsset($var, $where_query) {
        if(isset($var) && $var!="") {
            $this->where[] = $where_query;
        }
        return $this;
    }

    /**
     * @param string $order_by SQL Order By Syntax
     * @return ORM $this
     */
    public function orderBy($order_by) {
        $this->order_by = $order_by;
        return $this;
    }

    /**
     * @param string $group_by SQL Group By Syntax
     * @return ORM $this
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
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


    /**
     * @param $limit
     * @return array|null
     * @throws \Exception
     */
    public function paginate($limit) {
        $this->createConnection();

        if($this->config['driver'] == "mysql") {
            $query = (new Mysql($this->connection, $this->table, $this->select, $this->primary_key, $this->join, $this->join_type, $this->where, $limit, $this->offset, $this->order_by, $this->group_by, $this->having))->paginate();

            // Generate Pagination
            $page = request_int('page', 1);
            $query['links'] = $this->paginationHTML($page, $query['total'], $limit);

            return $query;
        }

        return null;
    }

    private function paginationHTML($page, $total, $limit) {
        $result = "<ul class='pagination'>";
        if($page==1) {
            $result .= "<li class=\"disabled\"><a href=\"#\">First</a></li>
                <li class=\"disabled\"><a href=\"#\">&laquo;</a></li>";
        } else {
            $link_prev = ($page > 1) ? $page - 1 : 1;
            $result .= "<li><a href=\"".get_current_url(['page'=>1])."\">First</a></li>
                <li><a href=\"".get_current_url(['page'=>$link_prev])."\">&laquo;</a></li>";
        }

        // Generate link number
        $total_page = ceil($total / $limit);
        $total_number = 3;
        $start_number = ($page > $total_number) ? $page - $total_number : 1;
        $end_number = ($page < ($total_page - $total_number)) ? $page + $total_number : $total_page;

        for ($i = $start_number; $i <= $end_number; $i++) {
            $link_active = ($page == $i) ? 'class="active"' : '';
            $result .= "<li $link_active ><a href='".get_current_url(['page'=>$i])."'>".$i."</a></li>";
        }

        // Generate next and last
        if ($page == $total_page) {
            $result .= "<li class='disabled'><a href='#'>&raquo;</a></li>";
            $result .= "<li class='disabled'><a href='#'>Last</a></li>";
        } else {
            $link_next = ($page < $total_page) ? $page + 1 : $total_page;
            $result .= "<li><a href='".get_current_url(['page'=>$link_next])."'>&raquo;</a></li>";
            $result .= "<li><a href='".get_current_url(['page'=>$total_page])."'>Last</a></li>";
        }

        $result .= "</ul>";

        return $result;
    }
}