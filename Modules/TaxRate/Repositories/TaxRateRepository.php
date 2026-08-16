<?php

namespace Modules\TaxRate\Repositories;

use App\Enums\Status;
use Modules\TaxRate\Entities\TaxRate;

class TaxRateRepository implements TaxRateInterface
{
    public function get()
    {
        return TaxRate::where(function ($query) {
            if (!isSuperadmin()) :
                $query->where('business_id', business_id());
            endif;
        })->orderBy('position', 'asc')->paginate(10);
    }

    public function getTaxRate()
    {
        return TaxRate::where(function ($query) {
            if (!isSuperadmin()) :
                $query->where('business_id', business_id());
            endif;
        })->orderBy('position', 'asc')->get();
    }
    
    public function getActive($business_id)
    {
        return TaxRate::where('business_id', $business_id)->orderBy('position', 'asc')->where('status', Status::ACTIVE)->get();
    }

    public function getFind($id)
    {
        return TaxRate::find($id);
    }
    public function store($request)
    {
        try {
            if (isSuperadmin()) :
                $business_id   = $request->business_id;
            else :
                $business_id   = business_id();
            endif;
            $taxRate              = new TaxRate();
            $taxRate->business_id = $business_id;
            $taxRate->name        = $request->name;
            $taxRate->tax_rate      = $request->tax_rate;
            if (!blank($request->position)) :
                $taxRate->position    = $request->position;
            endif;
            $taxRate->status      = $request->status == 'on' ? Status::ACTIVE : Status::INACTIVE;
            $taxRate->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id, $request)
    {
        try {

            if (isSuperadmin()) :
                $business_id   = $request->business_id;
            else :
                $business_id   = business_id();
            endif;
            $taxRate              = TaxRate::find($id);
            $taxRate->business_id = $business_id;
            $taxRate->name        = $request->name;
            $taxRate->tax_rate      = $request->tax_rate;
            if (!blank($request->position)) :
                $taxRate->position    = $request->position;
            endif;
            $taxRate->status      = $request->status == 'on' ? Status::ACTIVE : Status::INACTIVE;
            $taxRate->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id)
    {
        return TaxRate::destroy($id);
    }
    public function statusUpdate($id)
    {
        try {
            $taxRate         = TaxRate::find($id);
            $taxRate->status = $taxRate->status == Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE;
            $taxRate->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getTaxRates($business_id)
    {
        return TaxRate::where(function ($query) use ($business_id) {
            if (isSuperadmin()) :
                $query->where('business_id', $business_id);
            else :
                $query->where('business_id', business_id());
            endif;
        })->orderBy('position', 'asc')->where('status', Status::ACTIVE)->get();
    }
}
