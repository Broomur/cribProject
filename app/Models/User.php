<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Album;
use App\Models\Child;
use App\Models\Article;
use App\Models\Picture;
use App\Models\Calendar;
use App\Models\Document;
use App\Models\Team;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'rgpd_accepted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function roles() 
    {
        return $this->belongsToMany(Role::class);
    }

    public function children() 
    {
        return $this->belongsToMany(Child::class)->withPivot('child_id');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function events()
    {
        return $this->hasMany(Calendar::class, 'user_id');
    }
    
    public function articles()
    {
        return $this->hasMany(Article::class, 'user_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function team()
    {
        return $this->hasMany(Team::class);
    }
}
