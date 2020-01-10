<?php


namespace App\Database;
use App\Book;


interface IBooksConnection
{
    public function insert(string $name, string $author):Book;
    public function exists(string $name, string $author);
    public function delete(Book $book):bool;
    public function update(Book $book):Book;
}