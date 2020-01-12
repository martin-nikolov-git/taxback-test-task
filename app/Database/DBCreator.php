<?php


namespace App\Database;

/**
 * Class which creates the table ran only once.
 * Requires the database to be created beforehand
 */
class DBCreator extends PostgresqlConnection
{
    /**
     * Method checks whether the table exists in the provided connection
     *
     * @params string $table The name of the table to check
     * @params string $schema The schema where to check for the table
     * @returns bool Whether the table exists or not
     */
    public function check_table_exists(string $table, string $schema='public'):bool
    {
        $result = pg_query_params($this->connection, 'SELECT EXISTS (
                SELECT 1
                FROM pg_tables
                WHERE tablename = $1
                AND schemaname = $2
            )',
            [$table, $schema]);

        if($result === false) {
            throw new \Exception("An error occurred when querying does \"$table\" exist on \"$schema\":\n" . pg_last_error($this->connection));
        }

        $value = pg_fetch_result($result,0,0);
        if($value === 't') {
            return true;
        }
        return false;
    }

    /**
     * Creates the books table we are going to use within the task
     */
    public function create_library_table() {
        if($this->check_table_exists("books")) {
            echo "\e[1;33m Table \"books\" already exists.\e[0m";
            return;
        }

        $statement = "CREATE TABLE books(
                id SERIAL PRIMARY KEY,
                name VARCHAR(128) NOT NULL,
                author VARCHAR(128) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL,
                UNIQUE(name, author)
            )";

        pg_query($this->connection, $statement);
        $latest_error = pg_last_error($this->connection);
        if(!empty($latest_error) || $latest_error === false) {
            throw new \Exception("An error occurred when creating the \"books\" table");
        }
        echo "\e[0;32m Table \"books\" created.\e[0m";
    }
}