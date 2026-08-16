<?php

namespace Modules\Currency\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Currency\Http\Requests\StoreRequest;
use Modules\Currency\Repositories\CurrencyInterface;
use Monarobase\CountryList\CountryListFacade as Countries;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{

    protected $repo;
    public function __construct(CurrencyInterface $repo)
    {
        $this->repo  = $repo;
    }
    public function index()
    {

        return view('currency::index');
    }
    
    public function getAllCurrencies(){
        $currencies    = $this->repo->get();
        return DataTables::of($currencies)
        ->addIndexColumn() 
        ->editColumn('country',function($currency){
            return @$currency->country;
        })
        ->editColumn('currency',function($currency){
            return @$currency->currency;
        })
        ->editColumn('code',function($currency){
            return @$currency->code;
        })
        ->editColumn('symbol',function($currency){
           return  @$currency->symbol;
        })
        ->editColumn('position',function($currency){
            return @$currency->position;
        })
        ->editColumn('status',function($currency){
            return @$currency->my_status;
        })
        ->editColumn('action',function($currency){
            $action = '';
            if(hasPermission('currency_update') || hasPermission('currency_delete') || hasPermission('currency_status_update')):
                  $action .= '<div class="dropdown">';
                  $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  $action .= '<i class="fa fa-cogs"></i>';
                  $action .= ' </a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        if(hasPermission('currency_status_update')):
                            $action .= '<a class="dropdown-item" href="'. route('settings.currency.status.update',$currency->id) .'">';
                            $action .=  $currency->status ==   \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>' ;
                            $action .=  @statusUpdate($currency->status);
                            $action .= '</a>';
                        endif;

                        if(hasPermission('currency_update')):
                            $action .=  '<a href="'. route('settings.currency.edit',@$currency->id) .'" class="dropdown-item" ><i class="fas fa-pen"></i>'.__('edit') .'</a>';
                        endif;
                        if(hasPermission('currency_delete')):
                            $action .= '<form action="'.route('settings.currency.delete',@$currency->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_currency') .'">';
                            $action .= method_field('delete');
                            $action .= csrf_field();
                            $action .= ' <button type="submit" class="dropdown-item"   >';
                            $action .= '<i class="fas fa-trash-alt"></i>'.__('delete');
                            $action .= '</button>';
                            $action .= '</form>';
                        endif;
                        $action .= '</div>';
                        $action .= '</div> ';
            else:
                return '...';
            endif;

            return $action;
        }) 
        ->rawColumns(['country', 'currency',  'code', 'symbol',  'position', 'status', 'action'])
        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $countries   = Config::get('currencies');
        return view('currency::create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('currency_added_successfully'),__('success'));
            return redirect()->route('settings.currency.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $countries   = Config::get('currencies');
        $currency  = $this->repo->getFind($id);
        return view('currency::edit',compact('countries','currency'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('currency_updated_successfully'),__('success'));
            return redirect()->route('settings.currency.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('currency_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    public function statusUpdate($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('currency_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
