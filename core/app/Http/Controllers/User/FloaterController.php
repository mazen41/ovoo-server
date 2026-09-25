<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Floater;
use Illuminate\Http\Request;

class FloaterController extends Controller
{
    public function index()
    {
        $pageTitle = 'Floater';
        $floaters  = Floater::where('user_id', getParentUser()->id)->latest()->paginate(getPaginate());
        return view('Template::user.floater.index', compact('pageTitle', 'floaters'));
    }

    public function create()
    {
        $pageTitle  = 'Create Floater';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.floater.create', compact('pageTitle', 'mobileCode', 'countries'));
    }

    public function floaterGenerate(Request $request)
    {
        $request->validate([
            'dial_code'  => 'required|string',
            'mobile'     => 'required|string',
            'message'    => 'required|string',
            'color_code' => 'required|string',
        ]);

        $user = getParentUser();

        if (!featureAccessLimitCheck($user->floater_limit)) {
            $notify = 'You’ve reached your floater widget generate limit. Please upgrade your plan to continue.';
            return responseManager("limit", $notify);
        }

        $floater             = new Floater();
        $floater->user_id    = $user->id;
        $floater->dial_code  = $request->dial_code;
        $floater->mobile     = $request->mobile;
        $floater->message    = $request->message;
        $floater->color_code = $request->color_code;
        $floater->save();

        decrementFeature($user, 'floater_limit');

        $message = "The floater widget generate successfully";
        return responseManager('floater', $message, 'success', [
            'floater' => $floater,
            'url'     => 'https://wa.me/' . $floater->dial_code . $floater->mobile . '?text=' . urlencode($floater->message),
            'script'  => view('Template::user.floater.floater_script', compact('floater'))->render(),
            'preview' => view('Template::user.floater.floater_preview', compact('floater'))->render(),
        ]);
    }

    public function deleteFloater($id)
    {
        $floater = Floater::where('user_id', getParentUser()->id)->findOrFail($id);
        $floater->delete();

        $notify[] = ['success', 'Floater Deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function getScript($id)
    {
        $floater = Floater::where('user_id', getParentUser()->id)->find($id);

        if (!$floater) {
            $notify = 'The floater widget is not found';
            return responseManager("limit", $notify);
        }

        $script  = view('Template::user.floater.floater_script', compact('floater'))->render();
        return responseManager("limit", "Floater widget script", 'success', [
            'script' => $script
        ]);
    }
}
