<?php


namespace App;


class Book
{
    public $name;
    public $author;
    public $created_at;
    public $updated_at;
    public $id;

    public function __construct(string $name, string $author, $created_at = null, $updated_at = null, $id = null)
    {
        $this->name = $name;
        $this->author = $author;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->id = $id;
    }

    public function prepare_insert():array
    {
        return [$this->name, $this->author];
    }

    public function prepare_update():array
    {
        return [$this->name, $this->author, $this->created_at, $this->updated_at, $this->id];
    }
}