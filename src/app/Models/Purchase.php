<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'addresses_id',
        'exhibition_id',
        'amount',
        'payment_method',
    ];

    /**
     * リレーション: 購入は1つの住所に属する
     */
    public function addresses()
    {
        return $this->belongsTo(Address::class);
    }

    public function exhibitions()
    {
        return $this->belongsTo(Exhibition::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
