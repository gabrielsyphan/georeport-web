<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Organ extends DataLayer
{
    public function __construct()
    {
        parent::__construct("geo_organs", [], 'id', false);
    }
}
