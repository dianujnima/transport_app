<?php

namespace App\Models;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, DianujHashidsTrait, SoftDeletes;

    protected $guard = 'user';
    
    protected $appends = ['image_url', 'cover_image_url'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'first_name', 'last_name', 'image', 'user_role', 'phone_no', 'platform', 'device_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier(){
        return $this->getKey();
    }
    
    public function getJWTCustomClaims(){
        return [];
    }

    public function getFullNameAttribute()
    {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    public function getUserTypeAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->user_role));
    }

    public function getFullAddressAttribute()
    {
        return $this->address. '<br>'.
        $this->city.', '.$this->state.' '.$this->zipcode.'<br>'.
        '<abbr title="Phone">P:</abbr> '.$this->phone_no;
    }

    public function getImageUrlAttribute(){
        return $this->image ? check_file($this->image, 'user') : null;
    }

    public function getCoverImageUrlAttribute(){
        return $this->cover_image ? check_file($this->cover_image, 'cover') : null;
    }
}