<?php

namespace clk528\NyuReport\Controller\WeChat;

use Illuminate\Routing\Controller;
use App\Models\UDWUser;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WeChatController extends Controller
{

    use AuthenticatesUsers;


    /**
     * @var \EasyWeChat\Work\Application
     */
    private $app;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->app = \EasyWeChat::work(config('nyu.report_wechat_config'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|void
     */
    function redirect(Request $request)
    {
        $this->loginValidator($request->all())->validate();

        $user = $this->app->oauth->detailed()->user();


        if ($this->attemptLogin2($user->getId())) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin2($netId)
    {
        if ($user = $this->getUser(['netId' => $netId])) {
            $this->guard()->login($user);
            return true;
        }
        return false;
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     *
     * @return Validator
     */
    protected function loginValidator(array $data)
    {
        return \Validator::make($data, [
            'code' => 'required',
        ]);
    }

    /**
     * @param array $credentials
     * @return Builder|Model|object|null|AuthenticatableContract
     */
    private function getUser(array $credentials)
    {
        return UDWUser::query()->where($credentials)->first();
    }

    /**
     * Send the response after the user was authenticated.
     * @param Request $request
     * @return RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        setcookie('Admin-Token', $request->session()->getId());

        return redirect(route('ill'));
    }

    /**
     * @param Request $request
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        abort(424, trans('auth.failed'));
    }
}
