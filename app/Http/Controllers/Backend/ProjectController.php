<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreRequest;
use App\Repositories\Project\ProjectInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $repo,$businessRepo,$branchRepo;
    public function __construct(ProjectInterface $repo,BusinessInterface $businessRepo,BranchInterface $branchRepo)
    {
        $this->repo         = $repo;
        $this->businessRepo = $businessRepo;
        $this->branchRepo   = $branchRepo;
    }

    public function index()
    {
        return view('backend.project.index' );
    }
    
    public function getAllProject(){
        $projects = $this->repo->all(); 
        return DataTables::of($projects)
        ->addIndexColumn() 
            ->editColumn('branch',function($project){
                return @$project->branch->name;
            })
            ->editColumn('title',function($project){
                return @$project->title;
            })
            ->editColumn('date',function($project){
                return @dateFormat($project->date);
            })
            ->editColumn('file',function($project){
                return '<a href="'. $project->uploaded_file .'" download="">'. __('download') .'</a>';
            })
            ->editColumn('description',function($project){
                return @$project->description;
            })
            ->editColumn('action',function($project){
                $action = '';
                if(hasPermission('project_update') || hasPermission('project_delete')):
                    $action .= '<div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= '<i class="fa fa-cogs"></i>';
                    $action .=  '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';              
                        if(hasPermission('project_update')):
                            $action .= '<a href="'.route('project.edit',$project) .'" class="dropdown-item"  ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                        endif;
                        if(hasPermission('project_delete')):
                            $action .= '<form   action="'. route('project.delete',$project) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'.__('delete_project') .'">';
                            $action .= method_field('delete');
                            $action .=  csrf_field();
                            $action .= ' <button type="submit" class="dropdown-item" >';
                            $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                            $action .=  '</button>';
                            $action .= '</form>';
                        endif;
                        $action .= '</div>';
                        $action .= '</div>';  
                else:
                    return '...';
                endif;
                return $action;
            })

        ->rawColumns(['branch', 'title', 'date', 'file', 'description', 'action'])
        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $businesses = $this->businessRepo->getAll(); 
        $branches   = $this->branchRepo->getAll(business_id());
        return view('backend.project.create',compact('businesses','branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {

        if(env('DEMO')) {
            Toastr::error(__('Store system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('project_added_msg'),__('success'));
            return redirect()->route('project.index');
        }else{
            Toastr::error(__('project_error_msg'),__('errors'));
            return redirect()->back();
        }
    }
 
    public function businessBranches(Request $request){
        $option = '<option disabled selected>'.__('select').' '.__('branch').'</option>';
        if($request->ajax()): 
            $branches   = $this->branchRepo->getAll($request->business_id); 
            foreach ($branches as  $branch) {
                $option .=   '<option value="'.$branch->id.'">'.$branch->name.'</option>';
            }
        endif;
        
        return $option; 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project      = $this->repo->get($id); 
        $businesses   = $this->businessRepo->getAll();
        $branches     = $this->branchRepo->getAll($project->business_id);
        return view('backend.project.edit', compact('project','businesses','branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, $id)
    {
        if(env('DEMO')) {
            Toastr::error(__('Store system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request,$id)){
            Toastr::success(__('project_update_msg'),__('success'));
            return redirect()->route('project.index');
        }else{
            Toastr::error(__('project_error_msg'),__('errors'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('Delete system is disable for the demo mode.'),__('errors'));
            return redirect()->back();
        }
        $this->repo->delete($id);
        Toastr::success(__('project_deleted_msg'),__('success'));
        return redirect()->route('project.index');
    }
}
