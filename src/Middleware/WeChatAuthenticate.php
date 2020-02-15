<?php


namespace clk528\NyuReport\Middleware;


use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Request;

class WeChatAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        return $this->isWeChat() ? $this->toLogin() : $this->toScan();
    }


    private function isWeChat(): bool
    {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? "no");
        return strpos($ua, 'micromessenger') ? true : false;
    }

    private function toScan()
    {
        $app = \EasyWeChat::work();


        $config = $app->getConfig();

        $param = [
            'appid' => $config['corp_id'],
            'agentid' => $config['agent_id'],
            'redirect_uri' => route('ill.wechat.redirect'),
            'state' => $this->makeState($app->request)
        ];

        return "https://open.work.weixin.qq.com/wwopen/sso/qrConnect?" . http_build_query($param);
    }

    private function toLogin()
    {
        $app = \EasyWeChat::work();
        return $app->oauth->redirect(route('ill.wechat.redirect'))->getTargetUrl();
    }

    function makeState(Request $request)
    {
        if (!$request->hasSession()) {
            return false;
        }

        $state = sha1(uniqid(mt_rand(1, 1000000), true));
        $session = $request->getSession();

        if (is_callable([$session, 'put'])) {
            $session->put('state', $state);
        } elseif (is_callable([$session, 'set'])) {
            $session->set('state', $state);
        } else {
            return false;
        }

        return $state;
    }
}
