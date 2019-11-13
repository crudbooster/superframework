<?php

namespace System\Commands;


use System\ORM\ORM;

class TableUsers extends Command
{

    public function run() {
        $query = (new ORM())->getInstance();

        // MySQL
        if(config("database.driver") == "mysql") {
            $query->exec("CREATE TABLE IF NOT EXISTS users (
              id INT(11) AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(255) NOT NULL,
              email VARCHAR(55) NOT NULL,
              password VARCHAR(50) NOT NULL
              );");
            echo "Table users has been created!";
        } else {
            echo "Sorry database driver ".config("database.driver")." is not supported!";
        }
    }

}