<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\ContactTag;
use App\Traits\ContactManager;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    use ContactManager;

    public function edit($id)
    {
        $user           = getParentUser();
        $contact        = Contact::where('user_id', $user->id)->findOrFail($id);
        $pageTitle      = "Edit Contact - " . $contact->fullName;
        $countries      = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $contactLists   = ContactList::where('user_id', $user->id)->orderBy('name')->get();
        $contactTags    = ContactTag::where('user_id', $user->id)->orderBy('name')->get();
        $existingTagId  = $contact->tags()->pluck('contact_tag_id')->toArray();
        $existingListId = $contact->lists()->pluck('contact_list_id')->toArray();

        return view('Template::user.contact.edit', compact('pageTitle', 'countries', 'contact', 'contactLists', 'contactTags', 'existingTagId', 'existingListId'));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'contact_ids'   => 'required|array',
            'contact_ids.*' => 'required|exists:contacts,id'
        ], [
            'contact_ids.required' => 'Please select at least one contact to delete.',
            'contact_ids.array'    => 'Invalid contact selection.',
            'contact_ids.*.exists' => 'One or more selected contacts do not exist.'
        ]);

        $user     = getParentUser();
        $contacts = Contact::where('user_id', $user->id)
            ->whereIn('id', $request->contact_ids)
            ->whereDoesntHave('conversation')
            ->get();


        $deletedCount = 0;
        $totalContact = $contacts->count();

        foreach ($contacts as $contact) {
            $contact->tags()->detach();
            $contact->lists()->detach();
            $contact->delete();
            $deletedCount++;
        }


        $notify[] = ['success', "$deletedCount contacts have been deleted successfully."];

        if ($totalContact > $deletedCount) {
            $remaining = $totalContact - $deletedCount;
            $notify[] = ['warning', "$remaining contacts could not be deleted because they are associated with existing conversations."];
        }

        return back()->withNotify($notify);
    }
}
