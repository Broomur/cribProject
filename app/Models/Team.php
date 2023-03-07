<?php

namespace App\Models;

use App\Models\User;
use App\Models\Album;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'path',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
