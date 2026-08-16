<?php
namespace App\Repositories\Role;
Interface RoleInterface{
    public function permissions();
    public function all();
    public function get();
    public function getAll();
    public function store($request);
    public function edit($id);
    public function update($request);
    public function delete($id);
}
