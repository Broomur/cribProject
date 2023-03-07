<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Model
{
    use HasFactory;

     /**
      * The attributes that are mass assignable.
      *
      * @var array<int, string>
      */
    protected $fillable = [
        'name',
        'date',
        'time',
        'description',
        'user_id',
        'exists',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
