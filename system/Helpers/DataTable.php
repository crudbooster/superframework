<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/7/2019
 * Time: 5:31 PM
 */

namespace System\Helpers;


use System\Models\Model;

class DataTable
{
    private $model;
    private $searchable_columns;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function searchable(array $columns)
    {
        $this->searchable_columns = $columns;
    }


    public function get() {
        $result = DB($this->model::getTableName());

        if($search = request('search')['value']) {
            if($this->searchable_columns) {
                $likes = [];
                foreach($this->searchable_columns as $column) {
                    $likes[] = $column." like '%".$search."%'";
                }
                $result->where("(".implode(" OR ",$likes).")");
            }
        }

        $order_column_idx = request('order')[0]['column'];
        $order_column = request('columns')[$order_column_idx]['data'];
        $order_column_dir = request('order')[0]['dir'];
        $result->orderBy("$order_column $order_column_dir");
        $result->offset(request_int('start'));
        $result->limit(request_int('length'));
        $data = $result->all();

        $result = DB($this->model::getTableName());
        $records_total = $result->count();

        $result = DB($this->model::getTableName());

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