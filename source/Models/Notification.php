<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Notification extends DataLayer
{
    public function __construct()
    {
        parent::__construct("geo_notifications", [], 'id', false);
    }
}
