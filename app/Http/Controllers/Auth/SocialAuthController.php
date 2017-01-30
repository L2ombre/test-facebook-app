<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Socialite;

class SocialAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SocialAuthController Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $userData = [
            'name'        => $user->getName(),
            'email'       => $user->getEmail(),
            'avatar'       => $user->getAvatar(),
            'provider'    => $provider,
            'provider_id' => $user->getId(),
            'access_token' => $user->token,
            'is_active' => 1,
        ];

        $authUser = $this->findOrCreateUser($userData);
        if ($authUser) {
            Auth::login($authUser, true);
        }
        return redirect()->to($this->redirectTo);
    }

    /**
     * Handle facebook deauhorization
     *
     */
    public function handleProviderDeAuthCallback(Request $request)
    {
        //set is_active to 0

        //return what facebook needs
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     *
     * @param  $user     Socialite user object
     * @return  User
     */
    public function findOrCreateUser($userData)
    {
        $authUser = User::where('provider_id', $userData['provider_id'])
            ->where('provider', $userData['provider'])
            ->where('access_token', $userData['access_token'])
            ->first();
        if (!is_null($authUser) && $authUser && $authUser->is_active) {
            //user has been found and is active
            return $authUser;
        }

        return User::create(
            $userData
        );
    }
}
