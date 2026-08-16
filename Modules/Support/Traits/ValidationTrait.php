<?php
namespace Modules\Support\Traits;
/**
 *  support validation
 */
trait ValidationTrait
{
    public function AdminSupportValidation($type){ 
        if($type):
         $validator = [
                        'message' => ['required']    
                    ];
        else:
            if(isSuperadmin()):
                $user_id    = ['required'];
            else:
                $user_id    = '';
            endif;

            $validator = [
                'user_id'       => $user_id,
                'service_id'    => ['required'],
                'department_id' => ['required'],
                'subject'       => ['required'],
                'priority'      => ['required'],
                'description'   => ['required'],
            ];
        endif;
        return $validator;
            
    }
    public function BusinessSupportValidation($type){ 
        if($type):
         $validator = [
                        'message' => ['required']    
                    ];
        else:
            if(business()):
                $user_id    = ['required'];
            else:
                $user_id    = '';
            endif;

            $validator = [
                'user_id'       => $user_id,
                'service_id'    => ['required'],
                'department_id' => ['required'],
                'subject'       => ['required'],
                'priority'      => ['required'],
                'description'   => ['required'],
            ];
        endif;
        return $validator;
            
    }
}
