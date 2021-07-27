<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct()
    {
        dd(__METHOD__);
    }
}
