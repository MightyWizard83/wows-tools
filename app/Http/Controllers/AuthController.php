<?php

namespace App\Http\Controllers;

use MightyWizard83\LaravelWargamingAuth\WargamingAuth;

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
            $user = $this->wargaming->user();

            //$user['id'];
            //$user['nickname'] ;
            
            return redirect('/');
        }

        return $this->redirectToWargaming();
    }
}