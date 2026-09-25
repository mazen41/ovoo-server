<?php

namespace App\Traits;

use App\Models\ContactTag;
use App\Models\Contact;
use Illuminate\Http\Request;

trait ContactTagManager
{
    public function list()
    {
        $pageTitle   = "Manage Contact Tag";
        $contactTags = ContactTag::where('user_id', getParentUser()->id)
            ->withCount('contacts')
            ->searchable(['name'])
            ->apiQuery();

        $view = "Template::user.contact_tags.list";

        return responseManager("contact_tag", $pageTitle, "success", [
            "pageTitle"    => $pageTitle,
            'view'         => $view,
            'contactTags'  => $contactTags
        ]);
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:40',
        ]);
        $user = getParentUser();

        $tagExists = ContactTag::where('user_id', $user->id)->whereNot('id', $id)->where('name', $request->name)->exists();

        if ($tagExists) {
            $message = "Contact tag name already exists";
            return responseManager("contact_tag_exists", $message, "error");
        }

        if ($id) {
            $message    = "Contact tag updated successfully";
            $contactTag = ContactTag::where('user_id', $user->id)->findOrFailWithApi("contact tag", $id);
        } else {
            $message    = "Contact tag created successfully";
            $contactTag = new ContactTag();
        }

        $contactTag->name    = $request->name;
        $contactTag->user_id = $user->id;
        $contactTag->save();

        return responseManager("contact_tag", $message, "success", [
            'data' => $contactTag,
            'type' => 'contact-tag'
        ]);
    }

    public function deleteTag($id)
    {
        $contactTag = ContactTag::where('user_id', getParentUser()->id)->findOrFailWithApi("contact tag", $id);

        if ($contactTag->contacts->count()) {
            $message = "This tag cannot be deleted because it is associated with one or more contacts";
            return responseManager("tag_not_deleted", $message, "error");
        }

        $contactTag->delete();
        $message = "Contact tag deleted successfully";
        return responseManager("tag_deleted", $message, "success");
    }

    /**
     * Assign or remove a single tag on a contact without touching any other
     * contact fields. Accepts optional tag_id; omitting it (or passing empty)
     * detaches all tags from the contact.
     */
    public function assignToContact(Request $request, $contactId)
    {
        $user    = getParentUser();
        $contact = Contact::where('user_id', $user->id)->find($contactId);

        if (!$contact) {
            return responseManager("not_found", "Contact not found", "error");
        }

        $tagId = $request->tag_id ?? null;

        if ($tagId) {
            $tag = ContactTag::where('user_id', $user->id)->find($tagId);
            if (!$tag) {
                return responseManager("not_found", "Tag not found", "error");
            }
            $contact->tags()->sync([$tagId]);
            $message = "Tag assigned successfully";
        } else {
            $contact->tags()->detach();
            $message = "Tag removed successfully";
        }

        return responseManager("tag_assigned", $message, "success");
    }
}
