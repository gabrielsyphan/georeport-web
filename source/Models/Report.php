<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Report extends DataLayer
{
    public function __construct()
    {
        parent::__construct("geo_reports", [], 'id', false);
    }
}
