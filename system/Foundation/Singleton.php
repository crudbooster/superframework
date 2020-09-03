<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/12/2019
 * Time: 5:49 PM
 */

namespace System\Foundation;


class Singleton
{
    private $data;

    public function getData($key)
    {
        return $this->data[$key];
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }



}