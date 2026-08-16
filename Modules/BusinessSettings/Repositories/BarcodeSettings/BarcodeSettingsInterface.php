<?php
namespace Modules\BusinessSettings\Repositories\BarcodeSettings;

interface BarcodeSettingsInterface{
    public function get();
    public function getAll();
    public function getFind($id);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
}