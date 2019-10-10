# Super Framework
The lightweight and fastest PHP framework from the creator of CRUDBooster

## Installation

Requirements
- php 7.2 >= 
- Apache 2.4 >= 
- MySQL 8.0 / MariaDB 10.4 >= 
- Composer
- php Zend OPCache Extension (Optional but very Recommended for speed)

Installation command via **composer**: 
```bash
composer create-project superframework/superframework
```

## Database Configuration
app / Configs / database.php

## Module
Super framework use a modular MVC Concept. You have a default MVC after make a installation, that is /app/Modules/Site
If you want to make a new module, you can make a duplicate directory "Site" to your new module name. 

## Helper
| Helper Name | Description |
| ------------ | ----------- |
| abort($code = 404) | To terminate the process |
| logging($content, $type = "error") | To make a log |
| string_random($length = 6) | To make a random string |
| csrf_input() | To add hidden input about CSRF Token |
| session(["key"=>"value"]) | To set a session with array |
| session("key") | To retrieve session by a key |
| config("key") | To retrieve config by a key (from Configs/config.php)| 
| base_url($path = "") | To get the base url of your project, and you can set the suffix path |
| cache($key, $value, $cache_in_minutes = 60) | To make a cache by key and value, you can also set the cache duration in minutes | 
| cache($key) | To get the cache value by a key |
| json($array) | To return the json response by an array |
| json($array, $cache_in_minutes = 60) | Like a json() function but with a cache in minute |
| view($view_name, $data = [], $cache_in_minutes = 5) | To return a view that  you create in {module}/Views/{view_name}.php. You can assign the data array on second parameter |

## Database ORM
To make a database query on Super Framework, you can use DB() helper
 
| Name | Description |
| ----- | ----- |
| DB("table")->all($limit) | To get all table data (in array), and you can pass the limit |
| DB("table")->find($id) | To get the single record (in array) with a primary key value |
| DB("table")->where("status = 'Active'")->all($limit) | To get all table data with a condition |
| DB("table")->where("status = 'Active' AND price > 100000")->all($limit) | To get all table data with a multiple conditions. So you can write any condition in here, because this is a raw condition actually |
| DB("table")->select("id","name","status")->all() | To set the select of query | 
| DB("table")->addSelect("id")->addSelect("price")->all() | or Sometime you want to add more select in the next query, just add this method chain, before calling all() / find() | 
| DB("table")->limit($limit)->offset($offset)->all() | To get all table data with limit and offset |
| DB("table")->orderBy("id DESC")->all() | To get all table data with order by |
| DB("table")->groupBy("id, status")->all() | To get all table data with a group by fields |
| DB("table")->having("price > 10")->all() | To get al table data with having |
| DB("table")->join("categories ON categories.id = categories_id", $join_type = "LEFT JOIN")->all() | To get all data with a join, second param you can pass type of join (INNER JOIN, LEFT JOIN, RIGHT JOIN, OUTER JOIN) *mysql |
| DB("table")->with("categories")->all() | You can use this join alias, if you can make sure that the foreign key is meet the naming convention ( {table}_id ) | 
| DB("table")->insert($data_array) | To insert to the table with an array data | 
| DB("table")->where("id = {$id}")->update($data_array) | To update the record data |
| DB("table")->where("id = {$id}")->delete() | To delete record with a condition | 
| DB("table")->delete($id) | To delete record with primary key value | 
| DB("table")->delete() | To delete all record data |
  
