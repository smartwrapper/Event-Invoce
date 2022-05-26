<?php

namespace Classiebit\Eventmie\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Classiebit\Eventmie\Models\User;
use Facades\Classiebit\Eventmie\Eventmie;

use Classiebit\Eventmie\Notifications\MailNotification;

class ProfileController extends Controller
{
    
    public function __construct()
    {
        // language change
        $this->middleware('common');
    
        $this->middleware('auth');
    }

    public function index($view = 'eventmie::profile.profile', $extra = [])
    {
        $user  = $this->getAuthUser();
        return Eventmie::view($view, compact('user', 'extra'));
    }
    
    // get login user
    public function getAuthUser ()
    {
        return Auth::user();
    }

    // update user
    public function updateAuthUser (Request $request)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return error_redirect('Demo mode');
        }
        
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.Auth::id()
        ]);
        
        if(!empty($request->current))
        {
            $data = $this->updateAuthUserPassword($request);
        
            if($data['status'] == false)
            {
                return error_redirect($data['errors']);
            }
        }
        
        $user = User::find(Auth::id());

        $user->name                  = $request->name;
        $user->email                 = $request->email;
        $user->address               = $request->address;
        $user->phone                 = $request->phone;
        
        $this->updatebankDetails($request, $user);
        
        $user->save();

        // redirect no matter what so that it never turns back
        $msg = __('eventmie-pro::em.saved').' '.__('eventmie-pro::em.successfully');
        return success_redirect($msg, route('eventmie.profile'));
        
    }

    // reset password
    public function updateAuthUserPassword(Request $request)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return error_redirect('Demo mode');
        }
        $this->validate($request, [
            'current' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->current, $user->password)) {
            return ['errors' => __('eventmie-pro::em.current_password_not_match') , 'status' => false];
        }

        
        $user->password = Hash::make($request->password);
        $user->save();

        return ['status' => true];
    }

    // update user role
    public function updateAuthUserRole(Request $request)
    {
        $this->validate($request, [
            'organisation'  => 'required',
        ]);
        
        $manually_approve_organizer = (int)setting('multi-vendor.manually_approve_organizer');
        
        
        $user = User::find(Auth::id());
        
        // manually aporove organizer setting on then don't change role
        if(empty($manually_approve_organizer))
        {
            $user->role_id      = 3;
        } 

        $user->organisation = $request->organisation;

        $user->save();
    
        // ====================== Notification ====================== 
        // Manual Organizer approval email
        $msg[]                  = __('eventmie-pro::em.name').' - '.$user->name;
        $msg[]                  = __('eventmie-pro::em.email').' - '.$user->email;
        $extra_lines            = $msg;

        $mail['mail_subject']   = __('eventmie-pro::em.requested_to_become_organiser');
        $mail['mail_message']   = __('eventmie-pro::em.become_organiser_notification');
        $mail['action_title']   = __('eventmie-pro::em.view').' '.__('eventmie-pro::em.profile');
        $mail['action_url']     = route('eventmie.profile');
        $mail['n_type']         = "Approve-Organizer";
        if(empty($manually_approve_organizer))
        {
            // Became Organizer successfully email
            $msg[]                  = __('eventmie-pro::em.name').' - '.$user->name;
            $msg[]                  = __('eventmie-pro::em.email').' - '.$user->email;
            $extra_lines            = $msg;

            $mail['mail_subject']   = __('eventmie-pro::em.became_organiser_successful');
            $mail['mail_message']   = __('eventmie-pro::em.became_organiser_successful_msg');
            $mail['action_title']   = __('eventmie-pro::em.view').' '.__('eventmie-pro::em.profile');
            $mail['action_url']     = route('eventmie.profile');
            $mail['n_type']         = "Approved-Organizer";
        }
        
        // notification for
        $notification_ids       = [
            1, // admin
            $user->id, // logged in user by
        ];
        
        $users = User::whereIn('id', $notification_ids)->get();
        try {
            \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail, $extra_lines));
        } catch (\Throwable $th) {}
        // ====================== Notification ====================== 
        

        return redirect()->route('eventmie.profile');
    }

    // bank details update only for admin and orgainser
    public function updateBankDetails(Request $request, $user = [])
    {   
        $user->organisation        = $request->organisation;
        $user->bank_name           = $request->bank_name;
        $user->bank_code           = $request->bank_code;
        $user->bank_branch_name    = $request->bank_branch_name;
        $user->bank_branch_code    = $request->bank_branch_code;
        $user->bank_account_name   = $request->bank_account_name;
        $user->bank_account_number = $request->bank_account_number;
        $user->bank_account_phone  = $request->bank_account_phone;
    }
}