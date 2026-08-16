<?php
namespace Modules\BusinessSettings\Repositories;

use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Business\Entities\Business;

class BusinessSettingsRepository implements BusinessSettingsInterface{
    protected $uploadRepo;
    public function __construct(UploadInterface $uploadRepo)
    {
        $this->uploadRepo = $uploadRepo;
    }
    public function update($request){
       try {
            $business = Business::where(['id'=>$request->business_id])->first();
            $business->business_name           = $request->business_name;
            $business->start_date              = $request->start_date;
            if($request->logo):
            $business->logo                    = $this->uploadRepo->upload('business',$business->logo,$request->logo);
            endif;
            Session::forget('businessCurrency');
            $business->currency_id             = $request->currency;
            $business->sku_prefix              = $request->sku_prefix; 
            $business->default_profit_percent  = $request->default_profit_percent !="" ? $request->default_profit_percent:0;
            $business->barcode_type            = $request->barcode_type;
            $business->save();
            return true;
        } catch (\Throwable $th) {  
            return false;
        }
    }
}
