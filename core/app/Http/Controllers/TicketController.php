<?php

namespace App\Http\Controllers;

use App\Traits\SupportTicketManager;

class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->layout = 'frontend';
        $this->redirectLink = 'ticket.view';
        $this->userType     = 'user';
        $this->column       = 'user_id';
        $this->user = getParentUser();
        if ($this->user) {
            $this->layout = 'master';
        }
    }
}
