<?php
namespace App\Database;

use App\General\EnvReader;

class PostgresqlConnection implements IDBConnection
{
    /**
     * Available connection parameters. Each key is what is used in the pg_connect,
     * and each value is the one replaced from the .env file
     * @var array
     */
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

    /**
     * A postgre connection object
     */
    protected $connection = null;

    /**
     * Initialize the connection when constructing the object
     */
    public function __construct()
    {
        $this->connection = $this->createConnection();
    }

    /**
     * Connect to the setup postgre db
     */
    public function createConnection()
    {
        $connection_string = '';

        //Construct the connection string based on the values in the .env file
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

        //We set the connection timezone to the one defined in the .env file
        $timezone = EnvReader::get('APP_DEFAULT_TIMEZONE');
        pg_query($connection, "SET TIME ZONE '{$timezone}'");

        return $connection;
    }

    /**
     * Closes the connection
     */
    public function closeConnection()
    {
        if($this->connection !== null) {
            pg_close($this->connection);
        }
    }

    /**
     * Closes the connection on a destruct
     */
    public function __destruct()
    {
        $this->closeConnection();
    }
}