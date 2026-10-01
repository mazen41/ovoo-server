<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function index()
    {
        $pageTitle  = 'ShortLink';
        $shortLinks = ShortLink::where('user_id', getParentUser()?->id)->latest()->paginate(getPaginate());
        return view('Template::user.short_link.index', compact('pageTitle', 'shortLinks'));
    }
    public function edit($id)
    {
        $pageTitle  = 'Edit ShortLink';
        $shortLink  = ShortLink::where('user_id', getParentUser()?->id)->findOrFail($id);
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('Template::user.short_link.edit', compact('pageTitle', 'shortLink', 'mobileCode', 'countries'));
    }

    public function create()
    {
        $pageTitle  = 'Create ShortLink';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $code       = getTrx();

        return view('Template::user.short_link.create', compact('pageTitle', 'mobileCode', 'countries', 'code'));
    }

    public function checkCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $exists = ShortLink::where('code', $request->code)->exists();

        return apiResponse('link_check', 'success', [], [
            'exists' => $exists
        ]);
    }

    public function generateRandomCode()
    {
        do {
            $code = getTrx();
        } while (ShortLink::where('code', $code)->exists());

        return apiResponse('success', 'success',[], ['code' => $code]);
    }

    public function storeShortLink(Request $request, $id = 0)
    {
        $request->validate([
            'dial_code' => 'required|string',
            'mobile'    => 'required|numeric',
            'message'   => 'required|string',
            'code'      => "required|string|unique:short_links,code," . $id
        ]);

        $user = getParentUser();

        if (!$id) {
            $shortCode = $request->code;

            if (ShortLink::where('code', $shortCode)->exists()) {
                return responseManager('exists', "The shortLink code already exists");
            }

            if (!featureAccessLimitCheck($user->short_link_limit)) {
                $notify = 'You’ve reached your short link limit. Please upgrade your plan to continue.';
                return responseManager("limit", $notify);
            }

            $qrCodeUrl          = route('short.link.redirect', $shortCode);
            $message            = "The short link generate successfully";
            $shortLink          = new ShortLink();
            $shortLink->user_id = $user->id;
            $shortLink->code    = $shortCode;
            $shortLink->qr_code = $qrCodeUrl;
            
        } else {
            $shortLink = ShortLink::where('user_id', getParentUser()?->id)->find($id);
            if (!$shortLink) {
                $notify = 'The short link is not found';
                return responseManager("not_found", $notify,);
            }

            $qrCodeUrl = route('short.link.redirect', $shortLink->code);
            $message = "The short link updated successfully";
        }

        $shortLink->dial_code = $request->dial_code;
        $shortLink->mobile    = $request->mobile;
        $shortLink->message   = $request->message;
        $shortLink->save();

        if (!$id) {
            decrementFeature($user, 'short_link_limit');
        }

        return responseManager('short_link', $message, 'success', [
            'qr_code_url' => $qrCodeUrl,
            'qr_code_image' => cryptoQR($qrCodeUrl),
        ]);
    }

    public function delete($id)
    {
        $shortLink = ShortLink::where('user_id', getParentUser()?->id)->findOrFail($id);
        $shortLink->delete();
        $notify[] = ['success', 'ShortLink Deleted Successfully'];
        return back()->withNotify($notify);
    }
}
