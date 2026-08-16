<?php
namespace App\Repositories\Language;

use App\Enums\Status;
use App\Models\Backend\FlagIcon;
use App\Models\Backend\Language;
use App\Models\Backend\LanguageConfig;
use Illuminate\Support\Facades\File;

class LanguageRepository implements LanguageInterface{
    public function flags(){
        return FlagIcon::all();
    }
    public function activelang(){
        return Language::where('status', Status::ACTIVE)->get();
    }
    public function get(){
         return Language::orderByDesc('id')->get();
    }
    public function store($request){
        try {

            $language                   = new Language();
            $language->name             = $request->name;
            $language->code             = $request->code;
            $language->icon_class       = $request->icon_class;
            $language->text_direction   = $request->text_direction;
            $language->status           = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $language->save();

            $langConfig                 = new LanguageConfig();
            $langConfig->language_id    = $language->id;
            $langConfig->name           = $request->name;
            $langConfig->script         = $request->script;
            $langConfig->native         = $request->native;
            $langConfig->regional       = $request->regional;
            $langConfig->save();

            $path     = base_path('/lang/'.$request->code);

            if(!File::exists($path)):
                File::makeDirectory($path,0777,true,true);
                File::copyDirectory(base_path('/lang/en'),base_path('/lang/'.$request->code));

                //write existing data from phrase content
                $newJsonString     = file_get_contents(base_path('/lang/phrase.json'));
                File::put(base_path('/lang/'.$request->code.'.json'), stripslashes($newJsonString));
            endif;
 
            return true;
        } catch (\Throwable $th) {
             return false;
        }
    }
    public function edit($id){
         return Language::find($id);
    }
    public function update($request){
         try {

            $language                    = Language::find($request->id);
            if($language->code  != $request->code)://if not match old code and new code
                $oldFilePath             =  base_path('/lang/'.$language->code.'.json');
                $newFilePath             =  base_path('/lang/'.$request->code.'.json');
                $oldFolderPath           =  base_path('/lang/'.$language->code);
                $newFolderPath           =  base_path('/lang/'.$request->code);

                //rename file name
                if(!empty($oldFilePath)):
                    File::move($oldFilePath,$newFilePath);
                endif;
                //rename or make directory name
                if(File::exists($oldFolderPath)):
                    File::move($oldFolderPath,$newFolderPath);
                else:
                    File::makeDirectory($newFolderPath,0777,true,true);
                    File::copyDirectory(base_path('/lang/en'),$newFolderPath);
                endif;
            endif;

            $language->name             = $request->name;
            $language->code             = $request->code;
            $language->icon_class       = $request->icon_class;
            $language->text_direction   = $request->text_direction;
            $language->status           = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $language->save();

            $langConfig                 = LanguageConfig::where('language_id',$request->id)->first();
            $langConfig->language_id    = $language->id;
            $langConfig->name           = $request->name;
            $langConfig->script         = $request->script;
            $langConfig->native         = $request->native;
            $langConfig->regional       = $request->regional;
            $langConfig->save();


             return true;
         } catch (\Throwable $th) {
             return false;
         }
    }

    //edit phrase data
    public function editPhrase($id){
        try {
            $lang           = Language::find($id);
            $langConfig     = LanguageConfig::where('language_id',$id)->first();

            $path           = base_path('/lang/'.$lang->code);
            if(!File::isDirectory($path))://copied directory if does not exist directory
                File::makeDirectory($path,0777,true,true);
                File::copyDirectory(base_path('/lang/en'),base_path('/lang/'.$lang->code));
            endif;

            //read file
            if(File::exists(base_path('/lang/'.$lang->code.'.json'))):
                $getJsonData   = file_get_contents(base_path('/lang/'.$lang->code.'.json'));
            else:
                $newJsonData   = file_get_contents(base_path('/lang/phrase.json'));
                File::put(base_path('/lang/'.$lang->code.'.json'), stripslashes($newJsonData));
                $getJsonData   = file_get_contents(base_path('/lang/'.$lang->code.'.json'));
            endif;
            $langData          = json_decode($getJsonData,true);
            return $langData;
        } catch (\Throwable $th) {

            return [];
        }
    }


    //update phrase data
    public function updatePhrase($request,$code){
        try {
            $req_data   = $request->all();
            $req_data   = [];
            foreach ($request->all() as $key => $value) {
                $req_data[$key] = $value == null? "":$value;
            }
            $data       = array_change_key_case(array_slice($req_data,1));
            //write data in file
            $newJsonData= json_encode($data);
            File::put(base_path('/lang/'.$code.'.json'),$newJsonData);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    //language delete
    public function delete($id){
        $lang         = Language::find($id);
        $path         = base_path('/lang/'.$lang->code);
        if(File::exists($path)):
            File::deleteDirectory($path);
        endif;
        $jsonPath     = base_path('/lang/'.$lang->code.'.json');
        if(File::exists($jsonPath)):
            unlink($jsonPath);
        endif;
        return $lang->delete();
    }
}
