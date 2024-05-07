<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable=[
      'plant_id',
      'watering',
      'fertilizing',
      'other_care_instructions',
    ];
}
