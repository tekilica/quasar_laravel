<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public function userRole()
    {
        return $this->hasOne(UserRole::class);
    }

    public function userDetails()
    {
        return $this->hasOne(UserDetails::class);
    }

    public function uploadedImages()
    {
        return $this->hasMany(Image::class, 'uploaded_by');
    }

    public function lastEditedImages()
    {
        return $this->hasMany(Images::class, 'last_edited_by');
    }

    public function uploadedVideos()
    {
        return $this->hasMany(Video::class, 'uploaded_by');
    }

    public function lastEditedVideos()
    {
        return $this->hasMany(Video::class, 'last_edited_by');
    }

    public function uploadedSoundtracks()
    {
        return $this->hasMany(Soundtrack::class, 'uploaded_by');
    }

    public function lastEditedSoundtracks()
    {
        return $this->hasMany(Soundtrack::class, 'last_edited_by');
    }

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];
}
