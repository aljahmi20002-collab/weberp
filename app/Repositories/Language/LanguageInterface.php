<?php
namespace App\Repositories\Language;
Interface LanguageInterface {
    public function flags();
    public function get();
    public function activelang();
    public function store($request);
    public function edit($id);
    public function update($request);
    public function editPhrase($id);
    public function updatePhrase($request,$code);
    public function delete($id);
}
