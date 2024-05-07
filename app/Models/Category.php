<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable=[

        'name',
        'description',

    ];
    public function products(){
        $this->belongsToMany(Category::class,'plant_category');
    }
}
