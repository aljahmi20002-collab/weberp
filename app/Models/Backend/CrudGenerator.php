<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrudGenerator extends Model
{
    use HasFactory;
    protected $casts  = ['fields'=>'array'];
}
