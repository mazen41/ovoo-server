<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;

class SupportTicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {

        $this->userType = 'admin';
        $this->column   = 'admin_id';
        $this->user     = auth()->guard('admin')->user();
    }

    public function tickets()
    {
        $pageTitle = 'Support Tickets';
        $baseQuery = $this->baseQuery();

        if (request()->export) {
            return exportData($baseQuery, request()->export, "SupportTicket");
        }
        $tickets = $baseQuery->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('tickets', 'pageTitle'));
    }

    public function pendingTicket()
    {
        $pageTitle = 'Pending Tickets';
        $baseQuery = $this->baseQuery('pending');

        if (request()->export) {
            return exportData($baseQuery, request()->export, "SupportTicket");
        }
        $tickets = $baseQuery->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('tickets', 'pageTitle'));
    }

    public function closedTicket()
    {
        $pageTitle = 'Closed Tickets';
        $baseQuery = $this->baseQuery('closed');

        if (request()->export) {
            return exportData($baseQuery, request()->export, "SupportTicket");
        }
        $tickets = $baseQuery->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('tickets', 'pageTitle'));
    }

    public function answeredTicket()
    {
        $pageTitle = 'Answered Tickets';
        $baseQuery = $this->baseQuery('answered');

        if (request()->export) {
            return exportData($baseQuery, request()->export, "SupportTicket");
        }
        $tickets = $baseQuery->with('user')->paginate(getPaginate());

        return view('admin.support.tickets', compact('tickets', 'pageTitle'));
    }

    public function ticketReply($id)
    {
        $ticket    = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages  = SupportMessage::with('ticket', 'admin', 'attachments')->where('support_ticket_id', $ticket->id)->orderBy('id', 'desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }

    private function baseQuery($scope = 'query')
    {
        return SupportTicket::$scope()->searchable(['name', 'subject', 'ticket', 'user:username'])->filter(['status', 'priority'])->orderBy('id', getOrderBy());
    }

    public function ticketDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        $path    = getFilePath('ticket');
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                fileManager()->removeFile($path . '/' . $attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Support ticket deleted successfully"];
        return back()->withNotify($notify);
    }
}
