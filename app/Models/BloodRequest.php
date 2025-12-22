<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $fillable = [
        'hospital_id', 'blood_type', 'rhesus', 'quantity', 
        'urgency', 'patient_info', 'status', 'rejection_reason',
        'created_by', 'approved_by'
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}