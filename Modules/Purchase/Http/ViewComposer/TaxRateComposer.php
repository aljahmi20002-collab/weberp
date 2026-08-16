<?php
namespace Modules\Purchase\Http\ViewComposer;

use Modules\TaxRate\Repositories\TaxRateInterface;
use Illuminate\View\View;
class TaxRateComposer{
    protected $taxRepo;
    public function __construct(TaxRateInterface $taxRepo)
    {   
        $this->taxRepo = $taxRepo;
    }

    public function compose(View $view){
        $data  = $this->taxRepo->getActive(business_id());
        $view->with('taxRates',$data);
    }
}