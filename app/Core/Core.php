<?php

namespace App\Core;

class Core
{
    public function __construct()
    {
        dd($_SERVER['REQUEST_URI']);
    }
}
