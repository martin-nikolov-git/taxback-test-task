<?php

spl_autoload_register(function(string $className) {
    $file_path = getcwd() . "\\{$className}.php";
    if(file_exists($file_path)) {
        require_once($file_path);
    }
});

use App\General\EnvReader;

EnvReader::init();