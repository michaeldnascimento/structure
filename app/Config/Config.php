<?php

namespace App\Config;

class Config {

    public $mysql;
    public $sqlserver;

    public function __construct() {

        # mysql
       $this->mysql = $this->mysql();

        # sqlserver
       $this->sqlserver = $this->sqlserver();

    }


    public function mysql(): array
    {

        return [
            'driver' => env('MYSQL_DRIVE'),
            'host' => env('MYSQL_HOST'),
            'port' => env('MYSQL_PORT'),
            'dbname' => env('MYSQL_DATABASE'),
            'user' => env('MYSQL_USER'),
            'pass' => env('MYSQL_PASSWORD')
        ];
    }

    public function sqlserver(): array
    {
        return [
            'driver' => env('MSSQL_DRIVE'),
            'host' => env('MSSQL_HOST'),
            'port' => env('MSSQL_PORT'),
            'dbname' => env('MSSQL_DATABASE'),
            'user' => env('MSSQL_USER'),
            'pass' => env('MSSQL_PASSWORD')
        ];
    }



}
