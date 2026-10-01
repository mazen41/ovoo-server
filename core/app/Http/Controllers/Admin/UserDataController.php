<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\ContactTag;
use App\Models\ShortLink;

class UserDataController extends Controller
{
    public function contact()
    {
        $pageTitle = 'Manage Contact';
        $contacts  = Contact::latest('id')->with('user')->searchable(['user:username', 'mobile'])->paginate(getPaginate());
        return view('admin.user_data.contact', compact('pageTitle', 'contacts'));
    }

    public function contactList()
    {
        $pageTitle     = 'Manage Contact List';
        $contactLists  = ContactList::latest('id')->with('user')->searchable(['user:username', 'name'])->paginate(getPaginate());
        return view('admin.user_data.contact_list', compact('pageTitle', 'contactLists'));
    }

    public function contactTag()
    {
        $pageTitle    = 'Manage Contact Tag';
        $contactTags  = ContactTag::latest('id')->with('user')->withCount('contacts')->searchable(['user:username', 'name'])->paginate(getPaginate());
        return view('admin.user_data.contact_tag', compact('pageTitle', 'contactTags'));
    }

    public function campaign()
    {
        $pageTitle = 'Manage Campaign';
        $campaigns = Campaign::with('user')->latest('id')->searchable(['user:username', 'title'])->paginate(getPaginate());
        return view('admin.user_data.campaign', compact('pageTitle', 'campaigns'));
    }

    public function shortLink()
    {
        $pageTitle = 'Manage Short Link';
        $shortLinks = ShortLink::with('user')->latest('id')->searchable(['user:username','mobile'])->paginate(getPaginate());
        return view('admin.user_data.short_link', compact('pageTitle', 'shortLinks'));
    }
   
    
}
