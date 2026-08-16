<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Repositories\Language\LanguageInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LanguageController extends Controller
{
    protected $repo;
    public function __construct(LanguageInterface $repo){
        $this->repo = $repo;
    }
    public function index(){

        return view('backend.language.index');
    }
    
    public function getAllLanguage(){
        $languages      = $this->repo->get();
        return DataTables::of($languages)
        ->addIndexColumn() 
        ->editColumn('icon',function($lang){
            return '<i class="'.$lang->icon_class .'"></i>';
            
        })
        ->editColumn('icon_class',function($lang){
            return $lang->icon_class;
            
        })
        ->editColumn('lang_name',function($lang){
            return $lang->name;
        })
        ->editColumn('code',function($lang){
            return $lang->code;
        })
        ->editColumn('status',function($lang){
            return $lang->my_status;
        })
        ->editColumn('action',function($lang){
            $action = '';
            if(hasPermission('language_update') || hasPermission('language_delete')):
                $action .= '<div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= ' </a>';
                  $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                if(
                    $lang->code  !== 'en' &&
                    $lang->code  !== 'bn' &&
                    $lang->code  !== 'ar' && 
                    $lang->code  !== 'in' && 
                    $lang->code  !== 'he' && 
                    $lang->code  !== 'fr' &&
                    $lang->code  !== 'es' &&
                    $lang->code  !== 'tr' 
                ):
                    if(hasPermission('language_update')):
                        $action .=  '<a href="'. route('language.edit',$lang->id) .'" class="dropdown-item" data-bs-toggle="tooltip" title="'. __('edit') .'" ><i class="fas fa-pen"></i> '.__('edit').'</a>';
                    endif;
                endif;
                if(hasPermission('language_phrase')):
                    $action .= '<a href="'. route('language.edit.phrase',$lang->id) .'" class="dropdown-item" data-bs-toggle="tooltip" title="'. __('edit') .' '.__('phrase') .'" ><i class="fas fa-language"></i>'.__('edit').' '.__('phrase').'</a>';
                endif;
                if(
                        $lang->code  !== 'en' &&
                        $lang->code  !== 'bn' &&
                        $lang->code  !== 'ar' && 
                        $lang->code  !== 'in' && 
                        $lang->code  !== 'he' && 
                        $lang->code  !== 'fr' &&
                        $lang->code  !== 'es' &&
                        $lang->code  !== 'tr'
                    ):
                    if(hasPermission('language_delete')):
                        $action .=  '<form action="'. route('language.delete',$lang->id) .'" method="post" class="delete-form" id="delete" data-yes='.__('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_language') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item " data-bs-toggle="tooltip" title="'. __('delete') .'" >';
                        $action .= '<i class="fas fa-trash-alt"></i>';
                        $action .= __('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif;
                endif; 
                $action .= '</div>';
                $action .= '</div> ';
        else:
            return '...';
        endif;
            return $action;
        })
        ->rawColumns(['icon','lang_name', 'code', 'status', 'action'])
        ->make(true);
    }
    //language create page
    public function create(){
        $flags        = $this->repo->flags();
        return view('backend.language.create',compact('flags'));
    } 
    //language store
    public function store(StoreRequest $request){

        if(env('DEMO')) {
            Toastr::error(__('Store system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)):
            Toastr::success(__('language_added'),__('success'));
            return redirect()->route('language.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }
    public function edit($id){
        $lang       = $this->repo->edit($id);
        $flags       = $this->repo->flags();
        return view('backend.language.edit',compact('lang','flags'));
    }

    //language update
    public function update(UpdateRequest $request){

        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request)):
            Toastr::success(__('language_updated'),__('success'));
            return redirect()->route('language.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    //edit phrase
    public function editPhrase($id){ 
        if($this->repo->editPhrase($id)):
            $langData    = $this->repo->editPhrase($id);
            $lang        = $this->repo->edit($id);
            return view('backend.language.edit_phrase',compact('langData','lang'));
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }

    //update phrase
    public function updatePhrase(Request $request,$code){
        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
         if($this->repo->updatePhrase($request,$code)):
            Toastr::success(__('phrase_updated'),__('success'));
            return redirect()->route('language.index');
         else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
         endif;
    }
    //delete language
    public function delete($id){
        if(env('DEMO')) {
            Toastr::error(__('Delete system is disable for the demo mode.'),__('errors'));
            return redirect()->back();
        }
        if($this->repo->delete($id)):
            Toastr::success(__('language_deleted'),__('success'));
            return redirect()->route('language.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
    }
}
