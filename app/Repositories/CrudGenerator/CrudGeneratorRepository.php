<?php
namespace App\Repositories\CrudGenerator;

use App\Models\Backend\CrudGenerator;
use App\Repositories\CrudGenerator\CrudGeneratorInterface;
use Illuminate\Support\Facades\Schema;
class CrudGeneratorRepository implements CrudGeneratorInterface {
    public function store($request){
        try {
            // dd($request->all());
            if(Schema::hasTable(\Str::lower($request->model_name).'s')):
                return false;
            endif;
            $Crudgenerator             = new CrudGenerator();
            $Crudgenerator->title      = $request->title;
            $Crudgenerator->model_name = $request->model_name;
            $Crudgenerator->icon_class = $request->icon_class;
            $Crudgenerator->fields     = $request->field;
            $Crudgenerator->save();

            $fields='';
            foreach ($request->field as $key => $input) {
                $fields .= ' '.\Str::lower($input['field_name']).'#'.$input['db_type'].';';
            }

            $command = "crud:generate ".$request->model_name." --fields='".$fields."' --view-path=crudgenerator --controller-namespace=Crudgenerator  --form-helper=html";
            \Artisan::call($command);
            \Artisan::call('route:cache');
            \Artisan::call('migrate',['--force' => true]);

            return true;
        } catch (\Throwable $th) {
            dd($th);
            return false;
        }
    }

}
