<?php
namespace Modules\BusinessSettings\Repositories\BarcodeSettings;

use Modules\BusinessSettings\Entities\BarcodeSettings;
use Modules\BusinessSettings\Repositories\BarcodeSettings\BarcodeSettingsInterface;
class BarcodeSettingsRepository implements BarcodeSettingsInterface{
    public function get(){
        return BarcodeSettings::where('business_id',business_id())->get();
    }
    public function getAll(){
        return BarcodeSettings::where(function($query){
            $query->where('business_id',business_id());
            $query->orWhere('default',1);
        })->get();
    }

    public function getFind($id){
        return BarcodeSettings::find($id);
    }

    public function store($request){
        try {
            $barcode                   = new BarcodeSettings();
            $barcode->business_id      = business_id();
            $barcode->name             = $request->name;
            $barcode->paper_width      = $request->paper_width;
            $barcode->paper_height     = $request->paper_height;
            $barcode->label_width      = $request->label_width;
            $barcode->label_height     = $request->label_height;
            $barcode->label_in_per_row  = $request->label_in_per_row;

            $barcode->paper_width_type  = $request->paper_width_type;
            $barcode->paper_height_type = $request->paper_height_type;
            $barcode->label_width_type  = $request->label_width_type;
            $barcode->label_height_type = $request->label_height_type;
            $barcode->save();
            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }

    public function update($id,$request){
        try {
            $barcode                   = $this->getFind($id);
            $barcode->business_id      = business_id();
            $barcode->name             = $request->name;
            $barcode->paper_width      = $request->paper_width;
            $barcode->paper_height     = $request->paper_height;
            $barcode->label_width      = $request->label_width;
            $barcode->label_height     = $request->label_height;
            $barcode->label_in_per_row = $request->label_in_per_row;

            $barcode->paper_width_type  = $request->paper_width_type;
            $barcode->paper_height_type = $request->paper_height_type;
            $barcode->label_width_type  = $request->label_width_type;
            $barcode->label_height_type = $request->label_height_type;
            $barcode->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function delete($id){
        return BarcodeSettings::destroy($id);
    }

}