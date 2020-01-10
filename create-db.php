<?php
use App\Database\DBCreator;

require 'settings.php';

$creator = new DBCreator();
$creator->create_library_table();