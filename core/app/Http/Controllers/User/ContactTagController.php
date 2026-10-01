<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactTag;
use App\Traits\ContactTagManager;

class ContactTagController extends Controller
{
    use ContactTagManager;
}
