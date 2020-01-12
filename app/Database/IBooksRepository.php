<?php


namespace App\Database;
use App\Book;


/**
* Repository interface which ensures that whatever implements it will have the needed methods.
*/
interface IBooksRepository
{
    /**
     * Creates a book
     *
     * @params string $name Book title
     * @params string $author Book Author
     * @returns App\Book The newly created book object
     */
    public function insert(string $name, string $author):Book;
    
    /**
     * Checks does a book with a matching name and author exists
     *
     * @params string $name Book title
     * @params string $author Book Author
     * @returns App\Book The newly created book object
     */
    public function exists(string $name, string $author);

    /**
     * Updates a book
     *
     * @params App\Book $name Book to to update, update will be made based upon the id
     * @returns App\Book The newly updated book object
     */
    public function update(Book $book):Book;

    /**
     * Lists books
     *
     * @params integer $offset
     * @params integer $limit 
     * @returns array List of books
     */
    public function list(int $offset = 0, int $limit = 10):array;
    
     /**
     * Count of all books
     *
     * @returns int
     */
    public function count():int;

     /**
     * Count of all books from the author
     *
     * @returns int
     */
    public function count_filtered(string $author_name):int;

    /**
     * Lists books with an author name "like" the one provided
     *
     * @params string $author_name
     * @params integer $offset
     * @params integer $limit 
     * @returns array List of books
     */
    public function search(string $author_name, int $offset = 0, int $limit = 10):array;
}