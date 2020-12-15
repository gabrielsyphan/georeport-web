<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Upload extends DataLayer
{
    public function __construct()
    {
        parent::__construct("geo_uploads", [], 'id', false);
    }
}
