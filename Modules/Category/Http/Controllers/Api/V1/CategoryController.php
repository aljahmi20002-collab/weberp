<?php

namespace Modules\Category\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Category\Http\Requests\StoreRequest;
use Modules\Category\Http\Resources\v1\CategoryResource;
use Modules\Category\Repositories\CategoryInterface;

class CategoryController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo;
    public function __construct(CategoryInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        return $this->responseWithSuccess(__('success'), ['categories'=>  CategoryResource::collection($this->repo->getAll())], 200);
    }

    public function store(Request $request)
    {
        $validator = new StoreRequest();
        $validator = Validator::make($request->all(), $validator->rules());

        if ($validator->fails()) {
            return $this->responseWithError(__('error'), ['message' => $validator->errors()], 422);
        }

        if($this->repo->store($request)){
            return $this->responseWithSuccess(__('category_added_successfully'), [], 200);
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
        $category   = $this->repo->getFind($id);
        if($category) {
            return $this->responseWithSuccess(__('success'), ['category'=> new CategoryResource($category)], 200);
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
            return $this->responseWithSuccess(__('category_updated_successfully'), [], 200);
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
            return $this->responseWithSuccess(__('category_deleted_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

    public function statusUpdate($id){

        if($this->repo->statusUpdate($id)){
            return $this->responseWithSuccess(__('category_updated_successfully'), [], 200);
        }else{
            return $this->responseWithError(__('errors'), [], 500);
        }
    }

    public function parentCategories(Request $request){
        $parentCategories = $this->repo->getActiveCategory($request);
        return $this->responseWithSuccess(__('success'), ['parentCategories'=>  CategoryResource::collection($parentCategories)], 200);

    }
    public function subCategories($category_id){
        $subCategories = $this->repo->getSubcategory($category_id);
        return $this->responseWithSuccess(__('success'), ['subCategories'=>  CategoryResource::collection($subCategories)], 200);

    }
}
