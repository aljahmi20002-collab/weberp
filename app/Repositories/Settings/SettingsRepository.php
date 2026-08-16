<?php
namespace App\Repositories\Settings;

use App\Enums\Status;
use App\Models\Backend\Setting;
use App\Repositories\Settings\SettingsInterface;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Cache;

class SettingsRepository implements SettingsInterface
{
    protected $upload;
    public function __construct(UploadInterface $upload){
        $this->upload = $upload;
    }
    public function updateSettings($request){
        
         try {
           
                $ignore    = [];
                $ignore [] = '_token';
                $ignore [] = '_method';

                //mail settings
                if($request->mail_driver && $request->mail_driver == 'sendmail'):
                    $ignore [] ="mail_host";
                    $ignore [] ="mail_port";
                    $ignore [] ="mail_address";
                    $ignore [] ="mail_name";
                    $ignore [] = "mail_username";
                    $ignore [] ="mail_password";
                    $ignore [] ="mail_encryption";
                    $ignore [] ="signature";
                elseif($request->mail_driver && $request->mail_driver == 'smtp'):
                    $ignore[]='sendmail_path';
                endif;
                //end mail settings

                //social login settings
                if($request->facebook){
                    $ignore[]                  = "facebook";
                    $request['facebook_status']= $request->facebook_status == 'on'? Status::ACTIVE:Status::INACTIVE;
                }
                elseif($request->google){
                    $ignore[]                  = "google";
                    $request['google_status']= $request->google_status == 'on'? Status::ACTIVE:Status::INACTIVE;
                }
                //end social login settings
                elseif($request->recaptcha){
                    $ignore[]                  = "recaptcha";
                    $request['recaptcha_status']= $request->recaptcha_status == 'on'? Status::ACTIVE:Status::INACTIVE;
                }
                //end recaptcha
              
                foreach ($request->except($ignore) as $key => $value) {
                    $settings        = Setting::where('title',$key)->first();

                    if($settings){
                        if($key == 'logo'){
                            $logo              = Setting::where('title',$key)->first();
                            $settings->value  = $this->upload->upload('settings',$logo->value,$request->logo);
                        }elseif($key == 'favicon'){
                            $favicon           = Setting::where('title',$key)->first();
                            $settings->value  = $this->upload->upload('settings',$favicon->value,$request->favicon);
                        }elseif($key == 'table_empty_image'){
                            $table_empty_image           = Setting::where('title',$key)->first();
                            $settings->value  = $this->upload->upload('settings',$table_empty_image->value,$request->table_empty_image);
                        }elseif($key == 'table_search_image'){
                            $table_search_image           = Setting::where('title',$key)->first();
                            $settings->value  = $this->upload->upload('settings',$table_search_image->value,$request->table_search_image);
                        }else{
                            $settings->value   = $value;
                        }
                        $settings->save();
                    }else{
                        $settings          = new Setting();
                        $settings->title   = $key;
                        if($key == 'logo'){
                            $settings->value  = $this->upload->upload('settings','',$request->logo);
                        }elseif($key == 'favicon'){
                            $settings->value  = $this->upload->upload('settings','',$request->favicon);
                        }elseif($key == 'table_empty_image'){
                            $settings->value  = $this->upload->upload('settings','',$request->table_empty_image);
                        }elseif($key == 'table_search_image'){
                            $settings->value  = $this->upload->upload('settings','',$request->table_search_image);
                        }else{
                            $settings->value   = $value;
                        }

                        $settings->save();
                    }
                }

                 Cache::flush(); 
                return true;
         } catch (\Throwable $th) {

            return false;
         }
    }
}
