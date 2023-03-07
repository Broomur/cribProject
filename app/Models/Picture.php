<?php

namespace App\Models;

use App\Models\User;
use App\Models\Album;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Picture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'path',
        'user_id',
        'album_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function albums()
    {
        return $this->belongsTo(Album::class);
    }
}
