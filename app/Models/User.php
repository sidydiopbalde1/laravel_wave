<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'password',
        'role_id', 
        'telephone',
        'photo',
        'email',
        'code_secret',
        'solde',
        'plafond'
  

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relation avec le modèle Notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notifications::class);
    }

    /**
     * Transactions envoyées par l'utilisateur
     */
    public function sentTransactions()
    {
        return $this->hasMany(Transactions::class, 'sender_id');
    }

    /**
     * Transactions reçues par l'utilisateur
     */
    public function receivedTransactions()
    {
        return $this->hasMany(Transactions::class, 'receiver_id');
    }



    /**
     * Relation avec le modèle Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
