<?php

namespace Example\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Contact
 * @package Example\Models
 */
class User extends DataLayer
{
    /**
     * Contact constructor.
     */
    public function __construct()
    {
        parent::__construct("users", ["first_name", "last_name"]);
    }
}