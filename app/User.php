<?php

namespace App;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;


    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    public $transformer = UserTransformer::class;


    protected $table = 'users';        //  now Buyer an Seller models will use this table and wont create new table

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];


    // Mutator
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    // Accessor
    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    // Mutator
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }


    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode()
    {
        return str_random(40);
    }


    // Handling the model event by event observer approach.  // also learn event queueing
    public static function boot()
    {
        parent::boot();

        // Register an created model event with the dispatcher.
        parent::created(function($user) {

            // Mail::to($user)->send(new UserCreated($user)); // same as: Mail::to($user->email)->send(new UserCreated($user));

            retry(5, function() use ($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);

        });



        parent::updated(function($user) {
            if ($user->isDirty('email')) {

                //Mail::to($user)->send(new UserMailChanged($user));

                retry(5, function() use ($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);

            }
        });


    }

}
