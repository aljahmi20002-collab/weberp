<?php
namespace Modules\Currency\Repositories;
interface CurrencyInterface {
    public function get();
    public function getActiveAll();
    public function getFind($id);
    public function getSymbolWiseFind($symbol);
    public function store($request);
    public function update($id,$request);
    public function delete($id);
    public function statusUpdate($id);
}
