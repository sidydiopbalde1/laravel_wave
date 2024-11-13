<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
       'montant',
       'sender_id',
        'date',
        'frais',
        'type',
        'status',
        'id',
        "receiver_id",
        "user_id",
        "schedule_date",
        "receiver_phones"
    ];

        public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

}
