<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Resources\Api\V1\ProjectResource;
use App\Repositories\Project\ProjectInterface;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;

class ProjectController extends Controller
{
    use ApiReturnFormatTrait;
    protected $repo,$businessRepo,$branchRepo;
    public function __construct(ProjectInterface $repo,BusinessInterface $businessRepo,BranchInterface $branchRepo)
    {
        $this->repo         = $repo;
        $this->businessRepo = $businessRepo;
        $this->branchRepo   = $branchRepo;
    }

    public function index()
    {
        $projects = ProjectResource::collection($this->repo->all()); 
        return $this->responseWithSuccess(__('success'),[
            'projects'  => $projects
        ],200);
    }


    public function create()
    { 
        $branches   = BranchResource::collection($this->branchRepo->getAll(business_id()));
        return $this->responseWithSuccess(__('success'),[
            'branches'  => $branches
        ],200);
    }

    
    public function store(StoreRequest $request)
    { 
        if($this->repo->store($request)){ 
            return $this->responseWithSuccess(__('project_added_msg'),[],200);
        }else{ 
            return $this->responseWithError(__('project_error_msg'),[],400);
        }
    }
 
 
    public function edit($id)
    {
        $project      = new ProjectResource($this->repo->get($id));  
        $branches     = BranchResource::collection($this->branchRepo->getAll(business_id()));
        return $this->responseWithSuccess(__('success'),[
            'branches'  => $branches,
            'project'   => $project,
        ],200);
    }

    
    public function update(StoreRequest $request)
    {
        
        if($this->repo->update($request,$request->id)){ 
            return $this->responseWithSuccess(__('project_update_msg'),[],200); 
        }else{
            return $this->responseWithError(__('project_error_msg'),[],400);
        }
    }
 
    public function destroy($id)
    { 
        if($this->repo->delete($id)){ 
            return $this->responseWithSuccess(__('project_deleted_msg'),[],200); 
        }else{
            return $this->responseWithError(__('project_error_msg'),[],400);
        } 
    }
}
