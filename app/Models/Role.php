<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['libelle'];

    // Si vous avez une relation avec les utilisateurs, par exemple
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
