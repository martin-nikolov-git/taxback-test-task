<?php
namespace App\General;

class EnvReader
{
    private static $env = [];

    public static function init() {
        //Look for .env file either in env APP_ENV_PATH or in working directory
        $envpath = getenv('APP_ENV_PATH');
        if($envpath === false) {
            $envpath = getcwd() . '\.env';
        }

        if(!file_exists($envpath)) {
            return;
        }

        $file_handle = fopen($envpath, 'r');

        while(!feof($file_handle)) {
            $result = fgets($file_handle);
            if($result !== false) {
                self::handle_env_file_line($result);
            }
        }
        fclose($file_handle);
    }

    private static function handle_env_file_line(string $line) {
        $line = trim($line);

        //If either an empty line or a comment skip the line
        if(empty($line) || $line[0] === "#") {
            return;
        }

        $value_name = substr($line,0, strpos($line,'='));
        $value = substr($line, strpos($line,'=') + 1);

        self::$env[$value_name] = $value;
    }

    public static function get(string $variable_name) {
        $value = getenv($variable_name);

        if($value !== false) {
            return $value;
        }

        return self::$env[$variable_name] ?? null;
    }
}

EnvReader::init();