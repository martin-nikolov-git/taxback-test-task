<?php


namespace App\Database;


/**
* Repository interface which ensures that whatever implements it will have the needed methods.
*/
interface IDBConnection
{
    public function createConnection();
    public function closeConnection();
}