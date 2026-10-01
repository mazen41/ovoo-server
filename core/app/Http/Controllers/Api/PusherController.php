<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function authentication($socketId, $channelName)
    {
        $pusherSecret = config('app.PUSHER_APP_SECRET');
        $str          = $socketId . ":" . $channelName;
        $hash         = hash_hmac('sha256', $str, $pusherSecret);

        return response()->json([
            'message' => "Pusher authentication successfully",
            'auth'    => config('app.PUSHER_APP_KEY') . ":" . $hash
        ]);
    }

    public function authenticationApp(Request $request)
    {
        $pusherSecret = config('app.PUSHER_APP_SECRET');
        $str          = $request->socket_id . ":" . $request->channel_name;
        $hash         = hash_hmac('sha256', $str, $pusherSecret);

        return response()->json([
            'auth'    => config('app.PUSHER_APP_KEY') . ":" . $hash
        ]);
    }
}
