<?php

use App\LibraryReader;
use App\General\EnvReader;
use App\Database\BooksConnection;

require_once "settings.php";

$connection = new BooksConnection();
$folder = EnvReader::get("APP_LIBRARY_FOLDER");
$reader = new LibraryReader($folder, $connection);

$reader->readFolder();
