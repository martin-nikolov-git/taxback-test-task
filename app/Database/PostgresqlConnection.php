<?php
namespace App\Database;

use App\General\EnvReader;

class PostgresqlConnection implements IDBConnection
{
    private static $connection_parameters = [
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

    protected $connection = null;

    public function __construct()
    {
        $this->connection = $this->createConnection();
    }

    public function createConnection()
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

        $timezone = EnvReader::get('APP_DEFAULT_TIMEZONE');
        pg_query($connection, "SET TIME ZONE '{$timezone}'");

        return $connection;
    }

    public function closeConnection()
    {
        if($this->connection !== null) {
            pg_close($this->connection);
        }
    }

    public function __destruct()
    {
        $this->closeConnection();
    }
}