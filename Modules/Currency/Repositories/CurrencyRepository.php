<?php
namespace Modules\Currency\Repositories;

use App\Enums\Status;
use Modules\Currency\Entities\Currency;
use Modules\Currency\Repositories\CurrencyInterface;
class CurrencyRepository implements CurrencyInterface{
    public function get(){
        return Currency::orderBy('position','asc')->get();
    }
    public function getActiveAll(){
        return Currency::where('stauts',Status::ACTIVE)->orderBy('position','asc')->get();
    }
    public function getFind($id){
        return Currency::find($id);
    }
    public function getSymbolWiseFind($symbol){
        return Currency::where('symbol',$symbol)->first();
    }
    public function store($request){
        try {
            $request['status'] = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $request['position'] = $request->position !==""? $request->position: null;
            Currency::create($request->except('_token'));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {
            $request['status']  = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $request['position'] = $request->position !== ""? $request->position : null;
            Currency::where('id',$id)->update($request->except('_token','_method'));
            return true;
        } catch (\Throwable $th) {

            return false;
        }
    }
    public function delete($id){
        return Currency::destroy($id);
    }

    public function statusUpdate($id){
        try {
            $currency = Currency::find($id);
            $currency->status = $currency->status == Status::ACTIVE ? Status::INACTIVE:Status::ACTIVE;
            $currency->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }
}
