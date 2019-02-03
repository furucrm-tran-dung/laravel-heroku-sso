<?php

namespace App\Http\Controllers\Auth;

use App\Services\SocialAccountsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class SocialAccountController extends Controller
{

    public function redirectToProvider($provider) {
        /*dd(\Session::has("line_user_id"));
        if (\Session::has("line_user_id")) {
            return redirect()->route('register');
        }*/
        return \Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, SocialAccountsService $accountService, $provider)
    {
        try {
            $user = \Socialite::with($provider)->user();
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route('login')->with('status', ['type' => 'error', 'message' => __('customLabel.text_error_500')]);
        }

        $authUser = $accountService->find($user, $provider);

        if ($authUser) {
            auth()->login($authUser, true);
            return redirect()->route('home');
        } else {
            \Session::put("line_user_id", $user->id);
            return redirect()->route('register');
        }
    }
}