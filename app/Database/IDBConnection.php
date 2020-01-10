<?php


namespace App\Database;


interface IDBConnection
{
    public function createConnection();
    public function closeConnection();
}