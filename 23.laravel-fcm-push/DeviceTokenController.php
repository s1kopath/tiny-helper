<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends Controller
{
    public function __construct(private DeviceToken $fcmToken)
    {
        $this->fcmToken = $fcmToken;
    }

    // s jfd d fdsf sd f
    public function storeFCMToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $this->fcmToken->storeToken($request->token);

        return true;
    }
}
