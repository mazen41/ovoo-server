<?php

namespace App\Http\Controllers\ExternalApi;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search'        => 'nullable|string',
        ]);

        if ($validator->fails()) return apiResponse('validation_error', 'error', $validator->errors()->all());

        $user = getUserFromExternalAPIAccess();

        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);


        $contacts     = Contact::where('user_id', $user->id)
            ->searchable(['mobile', 'firstname', 'lastname'])
            ->searchable(['firstname', 'lastname', 'mobile', 'email'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return apiResponse('contact-list', 'success', ['Contact data fetched successfully.'], [
            'contacts' => $contacts
        ]);
    }


    public function save(Request $request, $id = 0)
    {
        $user = getUserFromExternalAPIAccess();

        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        $validator = Validator::make($request->all(), [
            'firstname'     => 'required|string|max:40',
            'lastname'      => 'required|string|max:40',
            'mobile_code'   => 'required',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'mobile'        => ['required', 'regex:/^([0-9]*)$/', Rule::unique('contacts')->ignore($id)->where('mobile_code', $request->mobile_code)->where('user_id', $user->id)],
            'city'          => 'nullable|string',
            'state'         => 'nullable|string',
            'post_code'     => 'nullable|string',
            'address'       => 'nullable|string',
        ]);

        if ($validator->fails()) return apiResponse('validation_error', 'error', $validator->errors()->all());

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $foundCountry = null;

        foreach ($countries as $countryCode => $countryData) {
            if (isset($countryData->dial_code) && $countryData->dial_code == $request->mobile_code) {
                $foundCountry = $countryData;
                break;
            }
        }

        if (!$id && !featureAccessLimitCheck($user->contact_limit)) {
            $notify = ['You’ve reached your ' . ' contact limit. Please upgrade your plan to continue.'];
            return apiResponse('contact_limit_error', 'error', $notify);
        }

        if ($id) {
            $contact = Contact::where('user_id', $user->id)->find($id);
            if (!$contact) return apiResponse('unknown_contact', 'error', ['The contact is not found']);

            $message =  " Contact updated successfully";
        } else {
            $message          = "Contact created successfully";
            $contact          = new Contact();
            $contact->user_id = $user->id;
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

        if ($request->hasFile('profile_image')) {
            try {
                $contact->image = fileUploader($request->profile_image, getFilePath('contactProfile'), getFileSize('contactProfile'), $contact->image);
            } catch (\Exception $exp) {
                return apiResponse('image_upload_error', 'error', ['Couldn\'t upload your image']);
            }
        }

        $contact->save();

        if ($id) decrementFeature($user, 'contact_limit');

        return apiResponse('contact_save', 'success', [$message], [
            'contact' => $contact
        ]);
    }

    public function delete($id)
    {
        $user    = getUserFromExternalAPIAccess();

        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        $contact = Contact::where('user_id', $user->id)->find($id);

        if (!$contact) return apiResponse('not_found', 'error', ['The contact not found']);

        if ($contact->conversation && $contact->conversation->messages()->count() > 0) {
            $notify = 'Unable to delete contact with messages';
            return apiResponse('contact_error', 'error', [$notify]);
        }

        if ($contact->is_blocked) return apiResponse('contact_error', 'error', ['Unable to delete contact which is blocked']);

        $contact->tags()->detach();
        $contact->lists()->detach();
        $contact->delete();
        return apiResponse('contact_deleted', 'success', ['Contact deleted successfully']);
    }
}
