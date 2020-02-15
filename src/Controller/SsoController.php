<?php


namespace clk528\NyuReport\Controller;


class SsoController
{
    function toSso()
    {
        $url = config('nyu.authorization_url');
        $data = [
            'scope' => 'openid',
            'response_type' => 'code',
            'redirect_uri' => route('nyu.login.redirect'),
            'client_id' => config('nyu.oauth_key'),
            'state' => 'jweun8t1c9v93uvjbczj'
        ];
        $redirect = $url . '?' . http_build_query($data);
        return redirect($redirect);
    }
}
