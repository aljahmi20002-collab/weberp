<?php

namespace App\Models\Backend;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['title','value'];



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('GeneralSetting')
        ->logOnly(['title', 'value'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}");
    }
}
