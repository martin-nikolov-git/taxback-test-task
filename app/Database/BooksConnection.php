<?php


namespace App\Database;
use App\Book;

class BooksConnection extends PostgresqlConnection implements IBooksConnection
{
    private static $statements = [
        "insert_book" => "INSERT INTO books (name, author) VALUES ($1, $2) RETURNING id",
        "exists_book" => "SELECT * FROM books WHERE name = $1 AND author = $2",
        "update_book" => "UPDATE books 
            SET name = $1, author = $2, created_at = $3, updated_at = $4
            WHERE id = $5
            RETURNING *",
        "delete_book" => "DELETE FROM books WHERE id = $1"
    ];

    public function __construct()
    {
        parent::__construct();

        foreach(self::$statements as $name => $sql) {
            $result = pg_prepare($this->connection, $name, $sql);
            $this->check_for_errors($result);
        }
    }

    public function insert(string $name, string $author):Book {
        $book = new Book($name, $author);
        $result = pg_execute($this->connection, 'insert_book', $book->prepare_insert());
        $this->check_for_errors($result);

        $book->id = (int) pg_fetch_result($result, 0,0);
        return $book;
    }

    public function exists(string $name, string $author)
    {
        $result = pg_execute($this->connection, 'exists_book', [$name, $author]);
        $this->check_for_errors($result);
        $result_object = pg_fetch_object($result);
        if($result_object == false) {
            return false;
        }

        return new Book($result_object->name,
            $result_object->author,
            $result_object->created_at,
            $result_object->updated_at,
            $result_object->id);
    }

    public function delete(Book $book):bool {
        return false;
    }

    public function update(Book $book):Book {

        $result = pg_execute($this->connection, 'update_book', $book->prepare_update());
        $this->check_for_errors($result);
        $result_object = pg_fetch_object($result);

        return new Book($result_object->name,
            $result_object->author,
            $result_object->created_at,
            $result_object->updated_at,
            $result_object->id);
    }

    private function check_for_errors($result) {
        if($result === false) {
            $pg_error = pg_last_error($this->connection);
            throw new \Exception($pg_error);
        }
    }
}