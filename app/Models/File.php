<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class File extends Model
{
    use HasFactory;

    public $table='files';
    protected $fillable = [
        'file',
    ];
}
