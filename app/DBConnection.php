<?php
namespace App;

use App\General\EnvReader;

class DBConnection
{
    public static $connection_parameters = [
        'host' => 'DB_HOST',
        'hostaddr' => 'DB_HOST_ADDR',
        'port' => 'DB_PORT',
        'dbname' => 'DB_DATABASE',
        'user' => 'DB_USERNAME',
        'password' => 'DB_PASSWORD',
        'connect_timeout' => 'DB_TIMEOUT',
        'options' => 'DB_OPTIONS',
        'tty' => 'DB_TTY',
        'sslmode' => 'DB_SSLMODE',
        'requiressl' => 'DB_REQUIRE_SSL',
        'service' => 'DB_SERVICE'
    ];

    private $connection = null;

    public function __construct()
    {
        $connection_string = '';

        foreach(self::$connection_parameters as $parameter => $env_key) {
            $value = EnvReader::get($env_key);
            if($value === null) {
                continue;
            }

            $connection_string .= "{$parameter}={$value} ";
        }

        $connection = pg_connect($connection_string);
        if($connection === false) {
            throw new \Exception("An error occurred when connecting to the DB");
        }
        $this->connection = $connection;
    }

    public function __destruct()
    {
        if($this->connection !== null) {
//            $this->connection
            pg_close($this->connection);
        }
    }

    public function check_table_exists(string $table, string $schema='public'):bool
    {
        $result = pg_query_params($this->connection, 'SELECT 1
            FROM pg_tables
            WHERE tablename = $1
            AND schemaname = $2',
            [$table, $schema]);

        if($result === false) {
            throw new \Exception("An error occurred when querying does \"$table\" exist on \"$schema\":\n" . pg_last_error($this->connection));
        }

        $value = pg_fetch_result($result,0,0);
        if($value === '1') {
            return true;
        }
        return false;
    }


}