<?php

namespace App\Traits;

use App\Constants\Status;
use App\Lib\WhatsApp\WhatsAppLib;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\ContactTag;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

trait ContactManager
{

    public $module = "contact";

    public function list()
    {
        $user        = getParentUser();
        $contactTags = ContactTag::where('user_id', $user->id)->orderBy('name')->get();
        $baseQuery   = Contact::where('user_id', $user->id)
            ->with('lists', 'tags', 'conversation')
            ->searchable(['mobile', 'firstname', 'lastname'])
            ->filter(['status'])
            ->orderBy('id', 'desc');

        $tagId = request()->tag_id;

        if ($tagId) {
            $baseQuery->whereHas('tags', function ($q) use ($tagId) {
                $q->where('contact_tags.id', $tagId);
            });
        }
        if ($this->module == 'customer') {
            $baseQuery->where('is_customer', Status::YES);
        }

        $contactLists = ContactList::where('user_id', $user->id)->orderBy('name')->get();
        $pageTitle    = "All " . $this->moduleNameTitle();
        $contacts     = $baseQuery->apiQuery();
        $view         = 'Template::user.' . $this->module . '.index';

        return responseManager($this->module, $pageTitle, "success", [
            'pageTitle'    => $pageTitle,
            'view'         => $view,
            'contacts'     => $contacts,
            'contactTags'  => $contactTags,
            'contactLists' => $contactLists,
            'profilePath' => getFilePath('contactProfile')
        ]);
    }

    public function create()
    {
        $user         = getParentUser();
        $pageTitle    = "Add " . ucfirst($this->module);
        $countries    = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $contactLists = ContactList::where('user_id', $user->id)->orderBy('name')->get();
        $contactTags  = ContactTag::where('user_id', $user->id)->orderBy('name')->get();
        $view         = 'Template::user.' . $this->module . '.create';

        return responseManager($this->module, $pageTitle, "success", [
            'pageTitle'    => $pageTitle,
            'view'         => $view,
            'countries'    => $countries,
            'contactLists' => $contactLists,
            'contactTags'  => $contactTags
        ]);
    }

    public function saveContact(Request $request, $id = 0)
    {
        $user = getParentUser();
        $request->validate([
            'firstname'     => 'required|string|max:40',
            'lastname'      => 'required|string|max:40',
            'mobile_code'   => 'required',
            'profile_image' => 'nullable|mimes:jpg,jpeg,png',
            'mobile'        => ['required', 'regex:/^([0-9]*)$/', Rule::unique('contacts')->ignore($id)->where('mobile_code', $request->mobile_code)->where('user_id', $user->id)],
            'city'          => 'nullable|string',
            'state'         => 'nullable|string',
            'post_code'     => 'nullable|string',
            'address'       => 'nullable|string',
            'tags'          => 'nullable|array',
            'lists'         => 'nullable|array',
            'attributes'    => 'nullable|array',
        ]);

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $foundCountry = null;
        foreach ($countries as $countryCode => $countryData) {
            if (isset($countryData->dial_code) && $countryData->dial_code == $request->mobile_code) {
                $foundCountry = $countryData;
                break;
            }
        }

        if (!$id && !featureAccessLimitCheck($user->contact_limit)) {
            $notify = 'You’ve reached your ' . $this->module . ' limit. Please upgrade your plan to continue.';
            return responseManager("contact_limit", $notify, "error");
        }

        if ($id) {
            $message = $this->moduleNameTitle() . " updated successfully";
            $contact = Contact::where('user_id', $user->id)->find($id);
            if (!$contact) {
                $notify = $this->moduleNameTitle() . ' not found';
                return responseManager("not_found", $notify, "error");
            }
        } else {
            $message          = $this->moduleNameTitle() . " created successfully";
            $contact          = new Contact();
            $contact->user_id = $user->id;
            if ($this->module ==  'customer') {
                $contact->is_customer = Status::YES;
            }
        }

        $contact->firstname   = $request->firstname;
        $contact->lastname    = $request->lastname;
        $contact->mobile_code = $request->mobile_code;
        $contact->mobile      = $request->mobile;
        $contact->address     = [
            'city'      => $request->city ?? '',
            'state'     => $request->state ?? '',
            'post_code' => $request->post_code ?? '',
            'address'   => $request->address ?? '',
            'country'   => $foundCountry->country ?? ''
        ];


        if ($request->custom_attributes && is_array($request->custom_attributes) && count($request->custom_attributes)) {
            $attributeNames  = $request->custom_attributes['name'] ?? [];
            $attributeValues = $request->custom_attributes['value'] ?? [];

            $attributeNames  = array_values(array_filter($attributeNames, fn($name) => !is_null($name) && $name !== ''));
            $attributeValues = array_values(array_filter($attributeValues, fn($value) => !is_null($value) && $value !== ''));

            if (count($attributeNames) === count($attributeValues) && count($attributeNames) > 0) {
                $contact->details = array_combine($attributeNames, $attributeValues);
            }
        } else {
            $contact->details = [];
        }

        if ($request->hasFile('profile_image')) {
            try {
                $old         = $contact->image;
                $contact->image = fileUploader($request->profile_image, getFilePath('contactProfile'), getFileSize('contactProfile'), $old);
            } catch (\Exception $exp) {
                $notify = 'Couldn\'t upload your image';
                return responseManager("upload_error", $notify, "error");
            }
        }

        $contact->save();
        if (!$id) {
            decrementFeature($user, 'contact_limit');
        }

        $contact->tags()->sync($request->tags ?? []);
        $contact->lists()->sync($request->lists ?? []);
        return responseManager("contact_created", $message, "success");
    }

    public function importContact(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:2048', "mimes:csv,xlsx"]
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $contactList = null;
        if ($request->contact_list_id) {
            $contactList = ContactList::where('user_id', getParentUser()->id)->find($request->contact_list_id);

            if (!$contactList) {
                return apiResponse('not_found', 'error', ['Contact list not found']);
            }
        }

        $references = [];

        if ($contactList) {
            $references['contact_list_id'] = $contactList->id;
        }

        $columnNames = ['firstname', 'lastname', 'mobile_code', 'mobile'];
        $notify = [];

        try {
            $import = importFileReader($request->file, $columnNames, $columnNames, references: $references);
            $notify[] = $import->notifyMessage();
            $status = "success";
        } catch (Exception $ex) {
            $status = "error";
            $notify[] = $ex->getMessage();
        }
        return apiResponse("contact_import", $status, $notify);
    }

    public function downloadCsv()
    {
        $filePath = "assets/export_templates/contact.csv";

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return responseManager("not_found", "File not found", "error");
    }

    public function deleteContact($id)
    {
        $contact = Contact::where('user_id', getParentUser()->id)->findOrFailWithApi('contact', $id);

        if ($contact->conversation && $contact->conversation->messages()->count() > 0) {
            $notify = 'Unable to delete ' . $this->module . ' with messages';
            return responseManager("contact_error", $notify, "error");
        }

        if ($contact->is_blocked) {
            $notify = "Unable to delete contact which is blocked";
            return responseManager("contact_error", $notify, "error");
        }

        $contact->tags()->detach();
        $contact->lists()->detach();
        $contact->delete();
        $notify = "Contact deleted successfully";
        return responseManager("contact_deleted", $notify, "success");
    }

    public function searchContact()
    {
        $user  = getParentUser();
        $query = Contact::where('user_id', $user->id)->whereDoesntHave('contactListContact')->searchable(['mobile', 'firstname', 'lastname']);

        $contacts = $query->apiQuery();
        return apiResponse("contact_search", "success", [], [
            'contacts' => $contacts,
            'more'     => $contacts->hasMorePages(),
        ]);
    }

    public function checkContact(Request $request, $id = 0)
    {
        $request->validate(['mobile_code' => 'required', 'mobile' => 'required']);
        $contact = Contact::where('user_id', getParentUser())->whereNot('id', $id)->where('mobile_code', $request->mobile_code)->where('mobile', $request->mobile)->first();

        if ($contact) {
            $exist['data'] = true;
        } else {
            $exist['data'] = false;
        }

        return response($exist);
    }

    public function edit($id)
    {
        $user           = getParentUser();
        $module         = $this->moduleNameTitle();
        $contact        = Contact::where('user_id', $user->id)->findOrFail($id);
        $pageTitle      = "Edit " . $module . " . - " . $contact->fullName;
        $countries      = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $contactLists   = ContactList::where('user_id', $user->id)->orderBy('name')->get();
        $contactTags    = ContactTag::where('user_id', $user->id)->orderBy('name')->get();
        $existingTagId  = $contact->tags()->pluck('contact_tag_id')->toArray();
        $existingListId = $contact->lists()->pluck('contact_list_id')->toArray();

        return view('Template::user.contact.edit', compact('pageTitle', 'countries', 'contact', 'contactLists', 'contactTags', 'existingTagId', 'existingListId', 'module'));
    }

    private function moduleNameTitle()
    {
        return ucfirst($this->module);
    }

    public function contactStatus(Request $request, $id)
    {
        $user = getParentUser();

        try {
            $contact = Contact::where('user_id', $user->id)->find($id);

            if (!$contact) {
                $notify[] = ['error', 'Contact not found'];
                return back()->withNotify($notify);
            }

            $whatsappAccount = $user->currentWhatsapp();

            if (!$whatsappAccount) {
                $notify[] = ['error', 'Please add a WhatsApp account first'];
                return back()->withNotify($notify);
            }

            $whatsappLib = new WhatsAppLib();

            if ($request->status === 'block') {
                $whatsappLib->userBlockAction($whatsappAccount, $contact);
                $contact->is_blocked = Status::YES;
                $contact->blocked_by = auth()->id();
                $message = 'Contact blocked successfully';
            } else {
                $whatsappLib->userBlockAction($whatsappAccount, $contact, 'unblock');
                $contact->is_blocked = Status::NO;
                $contact->blocked_by = 0;
                $message = 'Contact unblocked successfully';
            }

            $contact->save();

            $notify[] = ['success', $message];
            return back()->withNotify($notify);
        } catch (Exception $ex) {
            $notify[] = ['error', $ex->getMessage() ?? 'Something went wrong while updating contact status'];
            return back()->withNotify($notify);
        }
    }
}
