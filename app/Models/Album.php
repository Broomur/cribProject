<?php

namespace App\Models;

use App\Models\User;
use App\Models\Picture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'user_id',
        'exists',
    ];


    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}
