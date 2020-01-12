<?php


namespace App\Database;
use App\Book;
use App\Database\IBooksRepository;


/**
* Our PostgresBooksRepository
*/
class PostgresBooksRepository extends PostgresqlConnection implements IBooksRepository
{
    /**
     * The prepared statements, we are going to use.
     */
    private static $statements = [
        "insert_book" => "INSERT INTO books (name, author) VALUES ($1, $2) RETURNING id",
        "exists_book" => "SELECT * FROM books WHERE name = $1 AND author = $2",
        "update_book" => "UPDATE books 
            SET name = $1, author = $2, created_at = $3, updated_at = $4
            WHERE id = $5
            RETURNING *",
        "search_books" => "SELECT * FROM books WHERE author ILIKE $1 LIMIT $2 OFFSET $3",
        "count_books" => "SELECT COUNT(1) FROM books",
        "count_filtered_books" => "SELECT COUNT(1) FROM books WHERE author ILIKE $1",
        "list_books" => "SELECT * FROM books LIMIT $1 OFFSET $2",
        "delete_book" => "DELETE FROM books WHERE id = $1"
    ];

    /**
     * Prepares the statements, and checks for errors
     */
    public function __construct()
    {
        parent::__construct();

        foreach(self::$statements as $name => $sql) {
            $result = pg_prepare($this->connection, $name, $sql);
            $this->check_for_errors($result);
        }
    }

    /**
     * Creates a book
     *
     * @params string $name Book title
     * @params string $author Book Author
     * @returns App\Book The newly created book object
     */
    public function insert(string $name, string $author):Book {
        $book = new Book($name, $author);
        $result = pg_execute($this->connection, 'insert_book', $book->prepare_insert());
        $this->check_for_errors($result);

        $book->id = (int) pg_fetch_result($result, 0,0);
        return $book;
    }

    /**
     * Checks does a book with a matching name and author exists
     *
     * @params string $name Book title
     * @params string $author Book Author
     * @returns App\Book The newly created book object
     */
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

    /**
     * Updates a book
     *
     * @params App\Book $name Book to to update, update will be made based upon the id
     * @returns App\Book The newly updated book object
     */
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

    /**
     * Lists books
     *
     * @params integer $offset
     * @params integer $limit 
     * @returns array List of books
     */
    public function list(int $offset = 0, int $limit = 10):array 
    {
        $books_resource = pg_execute($this->connection, 'list_books', [$limit, $offset * $limit]);
        $this->check_for_errors($books_resource);
        
        $toReturn = [];

        while($row_object = pg_fetch_object($books_resource)) {
            $toReturn[] = new Book($row_object->name,
                $row_object->author,
                $row_object->created_at,
                $row_object->updated_at,
                $row_object->id);
        }

        return $toReturn;
    }

    /**
     * Lists books with an author name "like" the one provided
     *
     * @params string $author_name
     * @params integer $offset
     * @params integer $limit 
     * @returns array List of books
     */
    public function search(string $author_name, int $offset = 0, int $limit = 10):array
    {
        
        $books_resource = pg_execute($this->connection, 'search_books', ["%$author_name%", $limit, $offset * $limit]);
        $this->check_for_errors($books_resource);
        
        $toReturn = [];

        while($row_object = pg_fetch_object($books_resource)) {
            $toReturn[] = new Book($row_object->name,
                $row_object->author,
                $row_object->created_at,
                $row_object->updated_at,
                $row_object->id);
        }

        return $toReturn;
    }

    /**
     * Count of all books from the author
     *
     * @returns int
     */
    public function count_filtered(string $author_name):int
    {
        $book_count = pg_execute($this->connection, 'count_filtered_books', ["%$author_name%"]);
        $this->check_for_errors($book_count);

        return (int) pg_fetch_result($book_count, 0 , 0) ?? 0;
    }

    /**
     * Count of all books
     *
     * @returns int
     */
    public function count():int
    {
        $book_count = pg_execute($this->connection, 'count_books', []);
        $this->check_for_errors($book_count);

        return (int) pg_fetch_result($book_count, 0 , 0) ?? 0;
    }

    /**
     * Checks whether the returned resource is an indecation of an error,
     * if an error is encountered throw an exception
     */
    private function check_for_errors($result) 
    {
        if($result === false) {
            $pg_error = pg_last_error($this->connection);
            throw new \Exception($pg_error);
        }
    }
}