<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'birth_date',
        'start_date',
        'delivery_date',
        'prescriber',
        'phone',
        'care_types',
        'equipments',
        'sonde_followup',
        'stoma_followup',
        'wound_followup',
        'user_id'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'start_date' => 'date',
        'delivery_date' => 'date',
        'care_types' => 'array',
        'equipments' => 'array',
        'sonde_followup' => 'array',
        'stoma_followup' => 'array',
        'wound_followup' => 'array',
    ];
}
