<?php
class BaseModel
{
    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }
}