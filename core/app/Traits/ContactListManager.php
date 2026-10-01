<?php

namespace App\Traits;

use App\Models\Contact;
use App\Models\ContactList;
use App\Models\ContactListContact;
use Illuminate\Http\Request;

trait ContactListManager
{
    public function list()
    {
        $pageTitle    = "Manage Contact List";
        $contactLists = ContactList::where('user_id', getParentUser()->id)
            ->with('contact')
            ->searchable(['name'])
            ->orderBy('name', 'asc')
            ->apiQuery();

        $view = 'Template::user.contact_list.list';
        return responseManager("contact_list", $pageTitle, "success", [
            "pageTitle"    => $pageTitle,
            'view'         => $view,
            'contactLists' => $contactLists
        ]);
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:40',
        ]);

        $user = getParentUser();

        $contactListExists = ContactList::where('user_id', $user->id)->whereNot('id', $id)->where('name', $request->name)->exists();

        if ($contactListExists) {
            $message = "Contact list name already exists";
            return responseManager("contact_exists", $message, "error");
        }

        if ($id) {
            $message     = "Contact list updated successfully";
            $contactList = ContactList::where('user_id', $user->id)->findOrFailWithApi("contact list", $id);
        } else {
            $message     = "Contact list created successfully";
            $contactList = new ContactList();
        }

        $contactList->name    = $request->name;
        $contactList->user_id = $user->id;
        $contactList->save();

        return responseManager("contact_list", $message, "success", [
            'data' => $contactList,
            'type' => 'contact-list',
            'contacts' => $contactList->contact
        ]);
    }

    public function view($id)
    {
        $user        = getParentUser();
        $contactList = ContactList::where('user_id', $user->id)->with('contact')->findOrFailWithApi("contact list", $id);
        $contacts    = ContactListContact::withWhereHas('contact')
            ->searchable(['contact:mobile', 'contact:firstname', 'contact:lastname'])
            ->where('contact_list_id', $contactList->id)
            ->orderBy('id', 'desc')
            ->apiQuery();

        $pageTitle = "View Contact List" . ' - ' . $contactList->name;
        $view      = 'Template::user.contact_list.view_list';

        return responseManager("contact_list", $pageTitle, "success", [
            'pageTitle'   => $pageTitle,
            'view'        => $view,
            'contactList' => $contactList,
            'contacts'    => $contacts,
            'profilePath' => getFilePath('contactProfile')
        ]);
    }

    public function addContactToList(Request $request, $id)
    {
        $request->validate([
            'contacts' => 'required|array',
        ], [
            'contacts.required' => 'Please select at least one contact',
        ]);

        $user        = getParentUser();
        $contactList = ContactList::where('user_id', $user->id)->findOrFailWithApi("contact list", $id);
        $newContacts = [];

        $userContacts = Contact::whereIn('id', $request->contacts)->where('user_id', $user->id)->pluck('id')->toArray() ?? [];

        foreach ($userContacts as $userContact) {
            $contactListContact = ContactListContact::where('contact_list_id', $id)->where('contact_id', $userContact)->exists();
            if (!$contactListContact) {
                $newContacts[] = $userContact;
            }
        }

        $contactList->contact()->attach($newContacts, [
            'created_at' => now()
        ]);

        $message = count($newContacts) . " Contacts added successfully to " . $contactList->name;
        return responseManager("contact_added", $message, "success");
    }

    public function delete($id)
    {
        $contactList = ContactList::where('user_id', getParentUser()->id)->findOrFailWithApi("contact list", $id);

        if ($contactList->contact()->count()) {
            $message = "This list cannot be deleted because it is associated with one or more contacts";
            return responseManager("list_not_deleted", $message, "error");
        }
        $contactList->delete();

        $message = "Contact list deleted successfully";
        return responseManager("contact_list_deleted", $message, "success");
    }

    public function removeFromList($id)
    {
        $contactListContact = ContactListContact::withWhereHas('contact', function ($q) {
            $q->where('user_id', getParentUser()->id);
        })->findOrFailWithApi("contact", $id);
        $contactListContact->delete();

        $message = "Contact removed from contact list";
        return responseManager("contact_removed", $message, "success");
    }
}
