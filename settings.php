<?php

use App\General\EnvReader;

spl_autoload_register(function(string $className) {
    $file_path = getcwd() . "\\{$className}.php";
    if(file_exists($file_path)) {
        require_once($file_path);
    }
});


EnvReader::init();
$timezone = EnvReader::get('APP_DEFAULT_TIMEZONE');
if($timezone !== null) {
    $response = date_default_timezone_set($timezone);

    if($response == false) {
        throw new Exception("Invalid timezone set in enviroment");
    }
}