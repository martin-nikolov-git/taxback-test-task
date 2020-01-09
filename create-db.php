<?php
use App\DBConnection;

require 'settings.php';

$connection = new DBConnection();
if(!$connection->check_table_exists("books")) {
    #@TODO create table
}
