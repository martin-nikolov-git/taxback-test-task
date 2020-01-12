<?php

use App\General\EnvReader;

/**
 * A simple autoload function, which based on a class name requires the file
 */
spl_autoload_register(function(string $className) {
    $file_path = getcwd() . "\\{$className}.php";
    if(file_exists($file_path)) {
        require_once($file_path);
    }
});

// Initiliaze our EnvReader
EnvReader::init();

//Set the php scrip timezone.
$timezone = EnvReader::get('APP_DEFAULT_TIMEZONE');
if($timezone !== null) {
    $response = date_default_timezone_set($timezone);

    if($response == false) {
        throw new Exception("Invalid timezone set in enviroment");
    }
}