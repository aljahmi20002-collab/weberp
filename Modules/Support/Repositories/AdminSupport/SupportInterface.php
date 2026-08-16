<?php
namespace Modules\Support\Repositories\AdminSupport;
interface SupportInterface{
    public function get(); 
    public function getFind($id);
    public function chats($id);
    public function store($request);
    public function reply($request);
    public function update($id, $request);
    public function delete($id);  
    public function statusUpdate($id,$request);
}