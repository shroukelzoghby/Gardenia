<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plant extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'image',
        'type',
        'description',
        'light',
        'ideal_temperature',
        'resistance_zone',
        'suitable_location',
        'careful',
        'liquid_fertilizer',
        'clean',
        'toxicity',
        'names',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class,'plant_category');

    }

    public function schedules():HasMany
    {
        return $this->hasMany(Schedule::class);

    }

    public function favouriteByUsers()
    {
       return $this->belongsToMany(User::class,'user_favourite_plant','plant_id','user_id');

    }
}
