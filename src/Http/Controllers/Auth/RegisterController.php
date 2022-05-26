<?php

namespace Classiebit\Eventmie\Http\Controllers\Auth;
use Facades\Classiebit\Eventmie\Eventmie;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Notifications\MailNotification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         // language change
        $this->middleware('common');
        $this->middleware('guest');
        $this->redirectTo = \URL::previous();
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {   
        \Session::put('url.intended',\URL::previous());

        return Eventmie::view('eventmie::auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'accept' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user   = User::create([
                    'name'      => $data['name'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                    'role_id'  => 2,
                ]);

        // Send welcome email
        if(!empty($user->id))
        {
            // ====================== Notification ====================== 
            $mail['mail_subject']   = __('eventmie-pro::em.register_success');
            $mail['mail_message']   = __('eventmie-pro::em.get_tickets');
            $mail['action_title']   = __('eventmie-pro::em.login');
            $mail['action_url']     = eventmie_url();
            $mail['n_type']         = "user";

            // notification for
            $notification_ids       = [
                1, // admin
                $user->id, // new registered user
            ];
            
            $users = User::whereIn('id', $notification_ids)->get();
            if(checkMailCreds()) 
            {
                try {
                    \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail));
                } catch (\Throwable $th) {}
            }
            // ====================== Notification ======================     
        }
        
        $this->redirectTo = \Session::get('url.intended'); 
        
        return $user;
    }

    
    
}
