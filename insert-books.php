<?php

use App\LibraryReader;
use App\General\EnvReader;
use App\Database\PostgresBooksRepository;

require_once "settings.php";

$connection = new PostgresBooksRepository();
$folder = EnvReader::get("APP_LIBRARY_FOLDER");
$reader = new LibraryReader($folder, $connection);

$reader->readFolder();
