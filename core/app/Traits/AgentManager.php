<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\AgentPermission;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait AgentManager
{
    public function list()
    {
        $pageTitle = "Agent List";
        $user      = getParentUser();
        $agents    = User::agent()
            ->where('is_deleted', Status::NO)
            ->where('parent_id', $user->id)
            ->searchable(['firstname', 'lastname', 'email', 'username'])
            ->apiQuery();

        $view = "Template::user.agent.list";

        return responseManager("agent", $pageTitle, "success", [
            "pageTitle"   => $pageTitle,
            'view'        => $view,
            'agents'      => $agents,
            'profilePath' => getFilePath('userProfile'),
        ]);
    }

    public function save(Request $request)
    {
        $user = getParentUser();

        if (!featureAccessLimitCheck($user->agent_limit)) {
            $notify = 'You’ve reached your agent limit. Please upgrade your plan to continue.';
            return responseManager('not_available', $notify);
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        // Required: firstname, username, email. Everything else on the agent form is optional.
        // Empty form fields arrive as "" and ConvertEmptyStringsToNull turns them into null.
        // The username rule used to be listed twice (PHP keeps only the last key), so both parts
        // are merged here: required, string, unique, min 6.
        $request->validate([
            'firstname'    => 'required',
            'username'     => 'required|string|unique:users|min:6',
            'email'        => 'required|string|email|unique:users',
            'lastname'     => 'nullable|string',
            'country_code' => 'nullable|in:' . $countryCodes,
            'country'      => 'nullable|in:' . $countries,
            // A dial code is needed only when a mobile number is given.
            'mobile_code'  => 'required_with:mobile|in:' . $mobileCodes,
            'mobile'       => ['nullable', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
            'city'         => 'nullable|string',
            'state'        => 'nullable|string',
            'zip'          => 'nullable|string',
            'address'      => 'nullable|string',
        ]);

        $oneTimePassword = getNumber(10);

        $agent                   = new User();
        $agent->firstname        = $request->firstname;
        $agent->lastname         = $request->lastname;
        $agent->username         = $request->username;
        $agent->email            = $request->email;
        $agent->country_code     = $request->country_code;
        $agent->country_name     = @$request->country;
        // Store the dial code only together with a mobile number. Otherwise mobileNumber would be
        // just the dial code ("967"), which looks like a real number to SMS and other code.
        $agent->dial_code        = $request->filled('mobile') ? $request->mobile_code : null;
        $agent->mobile           = $request->mobile;
        $agent->city             = $request->city;
        $agent->state            = $request->state;
        $agent->zip              = $request->zip;
        $agent->address          = $request->address;
        $agent->parent_id        = $user->id;
        $agent->password         = Hash::make($oneTimePassword);
        $agent->kv               = Status::KYC_VERIFIED;
        $agent->ev               = Status::VERIFIED;
        $agent->sv               = Status::VERIFIED;
        $agent->tv               = Status::VERIFIED;
        $agent->profile_complete = Status::YES;
        $agent->is_agent         = Status::YES;
        $agent->save();

        decrementFeature($user, 'agent_limit');

        notify($agent, 'AGENT_REGISTERED', [
            'user'        => $agent->fullname,
            'parent_user' => $user->username,
            'username'    => $agent->username,
            'email'       => $agent->email,
            'password'    => $oneTimePassword,
            'login_url'   => route('user.login')
        ]);

        $message = "Agent created successfully";
        return responseManager("agent", $message, "success");
    }

    public function edit($id)
    {
        $user     = getParentUser();
        $agent    = User::agent()
            ->where('is_deleted', Status::NO)
            ->where('parent_id', $user->id)
            ->findOrFailWithApi("agent", $id);

        $pageTitle     = "Edit Agent - " . $agent->username;
        $view = "Template::user.agent.edit";
        return responseManager("agent", $pageTitle, "success", [
            "pageTitle"   => $pageTitle,
            "view"        => $view,
            "agent"       => $agent
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'nullable|string',
            'city'      => 'nullable|string',
            'state'     => 'nullable|string',
            'zip'       => 'nullable|string',
            'address'   => 'nullable|string',
        ]);
        $user  = getParentUser();
        $agent = User::agent()
            ->where('is_deleted', Status::NO)
            ->where('parent_id', $user->id)
            ->findOrFailWithApi("agent", $id);

        $agent->firstname = $request->firstname;

        // Optional fields change only when the request contains them, so a request that omits a
        // field never wipes the stored value. A field sent empty ("" -> null) clears it on purpose.
        foreach (['lastname', 'city', 'state', 'zip', 'address'] as $field) {
            if ($request->has($field)) {
                $agent->{$field} = $request->input($field);
            }
        }
        $agent->save();

        $message = "Agent updated successfully";
        return responseManager("agent", $message, "success");
    }

    public function permissions($id)
    {
        $user  = getParentUser();
        $agent = User::agent()
            ->where('is_deleted', Status::NO)
            ->where('parent_id', $user->id)
            ->findOrFailWithApi("agent", $id);

        $permissions         = AgentPermission::get();
        $existingPermissions = $agent->agentPermissions;
        $pageTitle           = "Agent Permissions - " . $agent->fullName;
        $view = "Template::user.agent.permissions";
        return responseManager("agent", $pageTitle, "success", [
            "pageTitle"           => $pageTitle,
            "view"                => $view,
            "agent"               => $agent,
            "permissions"         => $permissions,
            "existingPermissions" => $existingPermissions
        ]);
    }

    public function updatePermissions(Request $request, $id)
    {
        $request->validate([
            'permissions'   => "nullable|array|min:1",
            'permissions.*' => "nullable|integer",
        ]);

        $user = getParentUser();
        $agent = User::agent()
            ->where('is_deleted', Status::NO)
            ->where('parent_id', $user->id)
            ->findOrFailWithApi("agent", $id);

        $permissions = AgentPermission::whereIn('id', $request->permissions ?? [])->pluck('id')->toArray();
        $agent->agentPermissions()->sync($permissions);

        $message = "Agent permissions updated successfully";
        return responseManager("agent", $message, "success");
    }

    public function delete($id)
    {
        $user  = getParentUser();
        $agent = User::agent()
            ->where('is_deleted', Status::NO)
            ->where('parent_id', $user->id)
            ->findOrFailWithApi("agent", $id);

        $agent->is_deleted = Status::YES;
        $agent->save();

        $message = "Agent deleted successfully";
        return responseManager("agent", $message, "success");
    }
}
