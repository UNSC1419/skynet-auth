<?php

namespace Seat\UNSC1419\SkynetAuth\Http\Controllers;

use Illuminate\Http\Request;

class SkynetAuthController
{
    public function getsso(Request $request)
    {
        $SsoBackUrl = config('skynetauth.config.auth.sso_url');
        $remember_token = auth()->user()->remember_token;

        $AuthCallBackUrl = $SsoBackUrl .'?'. http_build_query([
                'remember_token' => $remember_token,
            ]);


        return redirect($AuthCallBackUrl);

    }
}