<?php
namespace App\Repositories\Project;

interface ProjectInterface {
    public function all();
    public function getAll();
    public function get($id);
    public function store($request);
    public function update($request,$id);
    public function delete($id);
}
