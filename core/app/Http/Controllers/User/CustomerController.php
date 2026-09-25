<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ContactManager;

class CustomerController extends Controller
{
    use ContactManager;

    public function __construct()
    {
        $this->module = "customer";
    }


}
