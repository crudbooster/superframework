<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/7/2019
 * Time: 5:31 PM
 */

namespace App\UtilDataTable;


use System\ORM\ORM;

class DataTable
{
    private $table;
    private $searchable_columns;
    private $query;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function query($query) {
        $this->query = $query;
    }

    public function searchable(array $columns)
    {
        $this->searchable_columns = $columns;
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function get() {
        $result = db($this->table);

        if(isset($this->query)) {
            $result = call_user_func($this->query, $result);
        }

        if($search = request('search')['value']) {
            if($this->searchable_columns) {
                $likes = [];
                foreach($this->searchable_columns as $column) {
                    $likes[] = $column." like '%".$search."%'";
                }
                $result->where("(".implode(" OR ",$likes).")");
            }
        }

        if(request('order')) {
            $order_column_idx = request('order')[0]['column'];
            $order_column = request('columns')[$order_column_idx]['data'];
            $order_column_dir = request('order')[0]['dir'];
        } else {
            $order_column = $this->table.".".(new ORM())->findPrimaryKey($this->table);
            $order_column_dir = "desc";
        }

        $result->orderBy("$order_column $order_column_dir");
        $result->offset(request_int('start'));
        $result->limit(request_int('length'));
        $data = $result->all();

        $no_start = request_int('start');
        foreach($data as &$item) {
            $no_start++;
            $item['_number'] = $no_start;
        }

        $result = db($this->table);
        if(isset($this->query)) {
            $result = call_user_func($this->query, $result);
        }
        $records_total = $result->count();

        $result = db($this->table);
        if(isset($this->query)) {
            $result = call_user_func($this->query, $result);
        }

        if($search = request('search')['value']) {
            if($this->searchable_columns) {
                $likes = [];
                foreach($this->searchable_columns as $column) {
                    $likes[] = $column." like '%".$search."%'";
                }
                $result->where("(".implode(" OR ",$likes).")");
            }
        }

        $records_total_filtered = $result->count();

        return [
            'draw'=> request_int('draw'),
            'recordsTotal'=> $records_total,
            'recordsFiltered'=> $records_total_filtered,
            'data'=> $data
        ];
    }

}