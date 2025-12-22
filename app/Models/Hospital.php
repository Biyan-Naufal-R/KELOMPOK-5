<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'email', 'status'
    ];

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
