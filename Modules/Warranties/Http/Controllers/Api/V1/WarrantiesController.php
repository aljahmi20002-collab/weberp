<?php

namespace Modules\Warranties\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Warranties\Http\Requests\StoreRequest;
use Modules\Warranties\Http\Resources\v1\WarrantiesResource;
use Modules\Warranties\Repositories\WarrantyInterface;

class WarrantiesController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(WarrantyInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        return $this->responseWithSuccess(__('success'), ['warranties'=>  WarrantiesResource::collection($this->repo->get())], 200);
    }

    public function store(Request $request)
    {
        $validator = new StoreRequest();
        $validator = Validator::make($request->all(), $validator->rules());

        if ($validator->fails()) {
            return $this->responseWithError(__('error'), ['message' => $validator->errors()], 422);
        }

        if($this->repo->store($request)){
            return $this->responseWithSuccess(__('warranty_added_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $warranty   = $this->repo->getFind($id);
        if($warranty) {
            return $this->responseWithSuccess(__('success'), ['warranty'=> new WarrantiesResource($warranty)], 200);
        }else {
            return $this->responseWithError(__('errors'), [], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = new StoreRequest();
        $validator = Validator::make($request->all(), $validator->rules());

        if ($validator->fails()) {
            return $this->responseWithError(__('error'), ['message' => $validator->errors()], 422);
        }

        if($this->repo->update($request->id,$request)){
            return $this->responseWithSuccess(__('warranty_updated_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if($this->repo->delete($id)){
            return $this->responseWithSuccess(__('warranty_deleted_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

    public function statusUpdate($id){

        if($this->repo->statusUpdate($id)){
            return $this->responseWithSuccess(__('warranty_updated_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }
}
