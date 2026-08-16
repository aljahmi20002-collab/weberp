<?php

namespace Modules\FundTransfer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Entities\Account;

class FundTransfer extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function fromAccount(){
        return $this->belongsTo(Account::class, 'from_account','id');
    }


    public function toAccount(){
        return $this->belongsTo(Account::class, 'to_account','id');
    }
 
}
