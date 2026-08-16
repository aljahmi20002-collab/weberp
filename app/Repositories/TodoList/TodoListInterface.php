<?php
namespace App\Repositories\TodoList;

interface TodoListInterface {
    public function users();
    public function all();
    public function userAllTodo($user_id);
    public function get($id);
    public function store($request);
    public function update($request,$id);
    public function delete($id,$request);
    public function statusUpdate($id,$request); 
    public function totalTodo($user_id);

}
