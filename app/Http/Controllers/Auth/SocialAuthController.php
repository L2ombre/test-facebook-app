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

//    use AuthenticatesUsers;

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
        $data = [
            'name'        => $user->getName(),
            'email'       => $user->getEmail(),
            'avatar'       => $user->getAvatar(),
            'provider'    => $provider,
            'provider_id' => $user->getId()
        ];

        Auth::login(User::firstOrCreate($data));
        return redirect()->to($this->redirectTo);

//        $user = Socialite::driver($provider)->user();
//
//        $authUser = $this->findOrCreateUser($user, $provider);
//        Auth::login($authUser, true);
//        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     *
     * @param  $user     Socialite user object
     * @param  $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = Auth::user()->where('provider_id', $user->id)->first();
        if (!is_null($authUser) && $authUser) {
            return $authUser;
        }
        return Auth::user()->create([
            'name'        => $user->name,
            'email'       => $user->email,
            'provider'    => $provider,
            'provider_id' => $user->id
        ]);
    }
}