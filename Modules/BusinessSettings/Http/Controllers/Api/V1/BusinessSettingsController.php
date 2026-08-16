<?php

namespace Modules\BusinessSettings\Http\Controllers\Api\V1;

use App\Traits\ApiReturnFormatTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Business\Http\Resources\v1\BusinessResource;
use Modules\Business\Repositories\BusinessInterface;
use Modules\BusinessSettings\Repositories\BusinessSettingsInterface;
use Modules\BusinessSettings\Http\Requests\UpdateRequest;
class BusinessSettingsController extends Controller
{
    use ApiReturnFormatTrait;

    protected $repo, $businessRepo;
    public function __construct(
        BusinessSettingsInterface $repo,
        BusinessInterface $businessRepo,
    ) {
        $this->repo                = $repo;
        $this->businessRepo        = $businessRepo;
    }

    public function businessInfoUpdate(UpdateRequest $request){
        $request['business_id'] = Auth::user()->business->id;
        if($this->repo->update($request)){ 
            return $this->responseWithSuccess(__('business_updated_successfully'),[],200);
        }else{ 
           return $this->responseWithError(__('error'),[],400);
        }
    }
    
}
