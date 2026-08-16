<?php

namespace Modules\BusinessSettings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarcodeSettings extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\BusinessSettings\Database\factories\BarcodeSettingsFactory::new();
    }
}
