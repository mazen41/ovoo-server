<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\ExternalApiIpWhiteList;
use Illuminate\Http\Request;

class IpWhiteListController extends Controller
{
    public function index()
    {

        $pageTitle = 'API IP Address White List';
        $user      = getParentUser();
        $ips       = ExternalApiIpWhiteList::where('user_id', $user->id)->searchable(['ip'])->latest()->paginate(getPaginate());

        return view('Template::user.ip_white_list', compact('pageTitle', 'user', 'ips'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'ip' => 'required|ip',
        ]);

        $user = getParentUser();

        if ($id) {
            $ipWhiteList = ExternalApiIpWhiteList::where('user_id', $user->id)
                ->firstOrFail();
            $message = 'IP address updated successfully';
        } else {
            $alreadyExist = ExternalApiIpWhiteList::where('user_id', $user->id)->where('ip', $request->ip)
                ->exists();

            if ($alreadyExist) {
                $notify[] = ['error', "IP address already exists"];
                return back()->withNotify($notify);
            }

            $ipWhiteList = new ExternalApiIpWhiteList();
            $message = 'New IP address added successfully';
            $ipWhiteList->user_id = $user->id;
        }

        $ipWhiteList->ip      = $request->ip;
        $ipWhiteList->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }


    public function delete($id)
    {
        $ip = ExternalApiIpWhiteList::where('user_id', getParentUser()->id)->find($id);

        if (!$ip) {
            $notify[] = ["error", "IP Address not found"];
            return back()->withNotify($notify);
        }

        $ip->delete();

        $notify[] = ["success", "IP Address deleted successfully"];
        return back()->withNotify($notify);
    }
}
