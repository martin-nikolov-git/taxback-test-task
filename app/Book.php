<?php


namespace App;

/**
 * Book object which is used all around the app.
 */
class Book
{
    /**
     * @var string The name of the book
     */
    public $name;
    
    /**
     * @var string The name of the author
     */
    public $author;

    /**
     * @var \DateTime A DateTime object representing when was the object added to the DB
     */
    public $created_at;

    /**
     * @var \DateTime A DateTime object representing when was the object last updated
     */
    public $updated_at;

    /**
     * @var int The object id
     */
    public $id;

    public function __construct(string $name, string $author, $created_at = null, $updated_at = null, $id = null)
    {
        $this->name = $name;
        $this->author = $author;
        $this->created_at = new \DateTime($created_at);
        $this->updated_at = ($updated_at !== null)? new \DateTime($updated_at) : null;
        $this->id = $id;
    }

    public function prepare_insert():array
    {
        return [$this->name, $this->author];
    }

    public function prepare_update():array
    {
        return [$this->name, 
            $this->author, 
            $this->created_at->format('Y-m-d H:i:s.u'), 
            $this->updated_at->format('Y-m-d H:i:s.u') ?? null, 
            $this->id];
    }
}