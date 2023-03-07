<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'PM_menu',
        'BB_menu',
        'snack',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
