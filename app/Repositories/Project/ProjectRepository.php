<?php
namespace App\Repositories\Project;

use App\Enums\BanUser;
use App\Models\Backend\Project;
use App\Models\Upload;
use App\Models\User;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProjectRepository implements ProjectInterface{

    protected $upload;
    public function __construct(UploadInterface $upload)
    {
        $this->upload   = $upload;
    }
    public function all(){
        return Project::with(['business','branch','business.user'])->where(function($query){
            if(business()): 
                $query->where('business_id',business_id());
            elseif(isUser()): 
                $query->where('business_id',business_id());
                $query->where('branch_id',Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->get();
    }
    public function getAll(){
        return Project::with(['business','branch','business.user'])->where(function($query){
            if(business()): 
                $query->where('business_id',business_id());
            elseif(isUser()): 
                $query->where('business_id',business_id());
                $query->where('branch_id',Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->get();
    }

    public function get($id){
        return Project::find($id);
    }
    public function store($request){
        try {
            $project               = new Project();
            if(isSuperadmin()):
                $business_id    = $request->business_id;
                $branch_id      = $request->branch_id;
            else:
                $business_id    = business_id();
                if(business()):
                    $branch_id      = $request->branch_id;
                else:
                    $branch_id      = Auth::user()->branch_id;
                endif;
            endif;
            $project->business_id  = $business_id;
            $project->branch_id    = $branch_id;
            $project->title        = $request->title;
            $project->date         = $request->date;
            if($request->file):
                $project->file     = $this->upload->upload('project','',$request->file);
            endif;
            $project->description  = $request->description;

            $project->save();
            return true;
        }
        catch (\Exception $e) {
       
            return false;
        }
    }

    public function update($request,$id)
    {
        try {
            $project               = Project::find($id);
            if(isSuperadmin()):
                $business_id    = $request->business_id;
                $branch_id      = $request->branch_id;
            else:
                $business_id    = business_id();
                if(business()):
                    $branch_id      = $request->branch_id;
                else:
                    $branch_id      = Auth::user()->branch_id;
                endif;
            endif;
            $project->business_id  = $business_id;
            $project->branch_id    = $branch_id;
            $project->title        = $request->title;
            $project->date         = $request->date;
            if($request->file):
                $project->file     = $this->upload->upload('project',$project->file,$request->file);
            endif;
            $project->description  = $request->description;
            $project->save();
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }


    public function delete($id){
        $project     = Project::find($id);
        if($project && $project->upload &&  File::exists($project->upload->original)):
            unlink(public_path($project->upload->original));
            Upload::destroy($project->file);
        endif;
        return Project::destroy($id);
    }

}
