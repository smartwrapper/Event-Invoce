<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Classiebit\Eventmie\Notifications\ForgotPasswordNotification;
use Classiebit\Eventmie\Models\Tag;

class User extends \TCG\Voyager\Models\User  implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // get customer when customer create booking or organiser create booking for customer
    public function get_customer($params = [])
    {
        return User::
            where([
                'id' => $params['customer_id'], 
            ])   
            ->first();   
    }

    // get user
    public function get_user($params = [])
    {
        return User::where($params)->first();   
    }

    // get organisers
    public function get_organisers()
    {
        return User::where(['role_id' => 3])->get();   
    }

    // total customers
    public function total_customers($user_id = null)
    {
        
        if(!empty($user_id))
        {
            return Booking::distinct('customer_id')->where(['organiser_id' => $user_id])->pluck('customer_id')->count();

        }

        return User::where(['role_id' => 2])->count();
    }
    
    // total organizers
    public function total_organizers($user_id = null)
    {
        if(!empty($user_id))
        {
            return User::where(['id' => $user_id])->count();

        }

        return User::where(['role_id' => 3])->count();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // ====================== Notification ====================== 
        //forgot password notification
        try {
            $this->notify(new ForgotPasswordNotification($token));
        } catch (\Throwable $th) {}
        // ====================== Notification ====================== 
    }

    // get all tags for particular organiser 
    public function get_tags($params = [])
    {   
        $user = User::find($params['organizer_id']);
        return $user->tags()->orderBy('updated_at', 'DESC')->paginate(10);
        
    }

    /**
     *  the tags belong to organiser means users
     */

    public function tags()
    {
        return $this->hasMany(Tag::class, 'organizer_id');
    }

    /**
     *  total user count
     */

    public function total_users()
    {
        return User::count();
    }

    /**
     * search tags
     */
    public function search_tags($params = [])
    {
        return User::with(['tags' => function ($query) use($params) {

                if(!empty($params['search']))
                {
                    $query->where(function($query) use($params) {

                        $query->orWhere('title','LIKE',"%{$params['search']}%");
                         
                    });
                }

                $query->limit(10);
               
            }])->where(['id' => $params['organizer_id'] ])->first()->tags;
       
    }
}
