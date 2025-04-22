<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'exhibition_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function exhibitions()
    {
        return $this->belongsTo(Exhibition::class);
    }
}
