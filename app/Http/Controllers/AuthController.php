<?php

namespace App\Http\Controllers;

use MightyWizard83\LaravelWargamingAuth\WargamingAuth;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @var WargamingAuth
     */
    protected $wargaming;

    /**
     * AuthController constructor.
     *
     * @param WargamingAuth $wargaming
     */
    public function __construct(WargamingAuth $wargaming)
    {
        $this->wargaming = $wargaming;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirectToWargaming()
    {
        return $this->wargaming->redirect();
    }

    /**
     * Get user info and log in (hypothetically)
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function handleWargamingCallback()
    {
        if ($this->wargaming->validate()) {
            $wgUser = $this->wargaming->user();

            //$user['id'];
            //$user['nickname'] ;
            
            $user = User::where('wargamingid', $wgUser['id'])->first();
            if (is_null($user)) {
                $user = User::create([
                    'name' => $wgUser['id'] . "-" . $wgUser['nickname'],
                    'nickname' => $wgUser['nickname'],
                    'wargamingid'  => $wgUser['id'],
                    'password'  => $wgUser['id'],
                    'email'  => $wgUser['id']
                ]);
            }
            Auth::login($user, true);
            
            
            return redirect('/');
        }

        return $this->redirectToWargaming();
    }
}