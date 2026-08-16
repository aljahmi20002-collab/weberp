<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrudGenerator\StoreRequest;
use App\Repositories\CrudGenerator\CrudGeneratorInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;


class CrudGeneratorController extends Controller
{
    protected $repo;
    public function __construct(CrudGeneratorInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(){

        return view('backend.crud-generator.index');
    }
    public function store(Request $request){
        if($this->repo->store($request)){
            Toastr::success(__('crud_generated'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
