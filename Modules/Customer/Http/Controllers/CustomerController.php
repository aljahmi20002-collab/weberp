<?php

namespace Modules\Customer\Http\Controllers;

use App\Repositories\User\UserInterface;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Customer\Http\Requests\StoreRequest;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Pos\Repositories\PosInterface;
use Modules\Sell\Repositories\SaleInterface;
use Modules\ServiceSale\Repositories\ServiceSaleInterface;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    protected $repo, $businessRepo, $saleRepo, $serviceSaleRepo,$posRepo;
    public function __construct(
            CustomerInterface $repo,
            BusinessInterface $businessRepo,
            SaleInterface $saleRepo,
            ServiceSaleInterface $serviceSaleRepo,
            PosInterface  $posRepo
        )
    {
        $this->repo            = $repo;
        $this->businessRepo    = $businessRepo;
        $this->saleRepo        = $saleRepo;
        $this->serviceSaleRepo = $serviceSaleRepo;
        $this->posRepo         = $posRepo;
    }

    public function index()
    {
        return view('customer::index');
    }

    public function getAllCustomers()
    {
        $customers   = $this->repo->getAllCustomers();
        return DataTables::of($customers)
            ->addIndexColumn()
            ->editColumn('customer', function ($customer) {
                $customerDetails = '';
                $customerDetails .= '<div class="d-flex align-items-center">';
                $customerDetails   .= '<div>';
                $customerDetails .=  '<img class="rounded-circle mr-10" src="' . @$customer->image . '" width="50"/>';
                $customerDetails .=  '</div>';
                $customerDetails .=  '<div>';
                $customerDetails .=  '<a class="text-primary" href="' . route('customers.view', $customer->id) . '">';
                $customerDetails .= @$customer->name . '</a> <br/>';
                $customerDetails .=  @$customer->email;
                $customerDetails .= '</div>';
                $customerDetails .= '</div>';
                return $customerDetails;
            })
            ->editColumn('opening_balance', function ($customer) {
                return businessCurrency($customer->business_id) . ' ' . @$customer->opening_balance;
            })
            ->editColumn('balance', function ($customer) {
                return businessCurrency($customer->business_id) . ' ' . @$customer->balance;
            })
            ->editColumn('status', function ($customer) {
                return  @$customer->my_status;
            })
            ->editColumn('action', function ($customer) {
                $action = '';

                $action .= ' <div class="dropdown">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .=  '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                if (hasPermission('customer_status_update')) :
                    $action .= ' <a class="dropdown-item" href="' . route('customers.status.update', $customer->id) . '">';
                    $action .=  $customer->status ==  \App\Enums\Status::ACTIVE ? '<i class="fa fa-ban"></i>' : '<i class="fa fa-check"></i>';
                    $action .=  @statusUpdate($customer->status);
                    $action .= '</a>';
                endif;
                $action .=  '<a href="' . route('customers.view', @$customer->id) . '" class="dropdown-item"  ><i class="fas fa-eye"></i>' . __('view') . '</a>';

                if (hasPermission('customer_update')) :
                    $action .= '<a href="' . route('customers.edit', @$customer->id) . '" class="dropdown-item"   ><i class="fas fa-pen"></i>' . __('edit') . '</a>';
                endif;
                if (hasPermission('customer_delete')) :
                    $action .=  '<form action="' . route('customers.delete', @$customer->id) . '" method="post" class="delete-form" id="delete" data-yes=' . __('yes') . ' data-cancel="' . __('cancel') . '" data-title="' . __('delete_customer') . '">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .= '<button type="submit" class="dropdown-item ">';
                    $action .= '<i class="fas fa-trash-alt"></i>' . __('delete');
                    $action .= '</button>';
                    $action .= '</form>';
                endif;
                $action .= '</div>';
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['customer', 'opening_balance', 'balance', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $businesses = $this->businessRepo->getAll();
        return view('customer::create', compact('businesses'));
    }

    public function store(StoreRequest $request)
    {
        if (env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        }

        if ($this->repo->store($request)) :
            Toastr::success(__('customer_store_successfully'), __('success'));
            return redirect()->route('customers.index');
        else :
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function edit($id)
    {
        $customer  = $this->repo->getFind($id);
        $businesses = $this->businessRepo->getAll();
        return view('customer::edit', compact('customer', 'businesses'));
    }

    public function update(StoreRequest $request)
    {
        if (env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        }

        if ($this->repo->update($request->id, $request)) :
            Toastr::success(__('customer_update_successfully'), __('success'));
            return redirect()->route('customers.index');
        else :
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function destroy($id)
    {
        if ($this->repo->delete($id)) :
            Toastr::success(__('customer_delete_successfully'), __('success'));
            return redirect()->back();
        else :
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function statusUpdate($id)
    {
        if (env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        }

        if ($this->repo->statusUpdate($id)) :
            Toastr::success(__('customer_updated_successfully'), __('success'));
            return redirect()->route('customers.index');
        else :
            Toastr::error(__('error'), __('errors'));
            return redirect()->back();
        endif;
    }

    public function view(Request $request, $id)
    {
        $customer          = $this->repo->getFind($id);
        $totalSale         = $this->repo->totalSalesPayments($customer->id);

        return view('customer::view', compact('customer', 'totalSale', 'request'));
    }

    // sale invoice and payment
    public function getInvoice($id)
    {
        $customer          = $this->repo->getFind($id);
        $invoices          = $this->saleRepo->getCustomerSales($customer->id);
        return DataTables::of($invoices)
            ->addIndexColumn()
            ->editColumn('date', function ($sale) {
                return  \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y');
            })
            ->editColumn('invoice_no', function ($sale) {
                return '<a href="' . route('invoice.view', $sale->id) . '" class="text-primary">' . @$sale->invoice_no . '</a>';
            })
            ->editColumn('branch', function ($sale) {
                return @$sale->branch->name;
            })
            ->editColumn('customer_details', function ($sale) {
                $customerDetails = '';
                $customerDetails .= '<div class="d-flex"> ';
                $customerDetails .= '<span>' . __('type') . '</span>    :' . __(\Config::get('pos_default.customer_type.' . @$sale->customer_type));
                $customerDetails .= '</div>';
                if ($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER) :
                    $customerDetails .= '<div class="d-flex"> ';
                    $customerDetails .= '<span>' . __('name') . '</span>    :' .  @$sale->customer->name;
                    $customerDetails .= '</div>';
                    $customerDetails .= '<div class="d-flex">';
                    $customerDetails .= '<span>' . __('email') . '</span> :' . @$sale->customer->email;
                    $customerDetails .= '</div>';
                    $customerDetails .= '<div class="d-flex">';
                    $customerDetails .= '<span>' . __('phone') . '</span>  :' . @$sale->customer->phone;
                    $customerDetails .= '</div>';
                    $customerDetails .= '<div class="d-flex">';
                    $customerDetails .=  '<span>' . __('address') . '</span>  : <span class="address">' .  @$sale->customer->address . '</span>';
                    $customerDetails .= '</div> ';
                endif;
                return $customerDetails;
            })
            ->editColumn('payment_status', function ($sale) {
                return @$sale->my_payment_status;
            })
            ->editColumn('total', function ($sale) {
                $total = '';
                $total .= '<span>' . __('total') . ':</span> ' . @businessCurrency($sale->business_id) . ' ' . @number_format($sale->total_sale_price, 2) . '<br/>';
                $total .= '<span class="text-success">' . __('paid') . ':</span>' . @businessCurrency($sale->business_id) . ' ' . @number_format($sale->payments->sum('amount'), 2) . '<br/>';
                $total .= '<span class="text-danger">' . __('due') . ':</span> ' . @businessCurrency($sale->business_id) . ' ' . number_format($sale->DueAmount, 2);
                return $total;
            })
            ->editColumn('action', function ($sale) {
                $action = '';
                if (hasPermission('invoice_view') || hasPermission('sale_read_payment')) :
                    $action .= '<div class="dropdown ">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= '<i class="fa fa-cogs"></i> ';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
                    if (hasPermission('invoice_view')) :
                        $action .= '<a href="' . route('invoice.view', $sale->id) . '" data-title="' . __('view') . '" class="dropdown-item "  ><i class="fa fa-eye"></i>' . __('view') . '</a>';
                    endif;

                    if (hasPermission('sale_read_payment')) :
                        $action .= '<a href="#" data-title="' . __('manage_sale_payment') . '" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="' . route('sale.manage.payment', $sale->id) . '"><i class="fa fa-hand-holding-dollar"></i>' . __('payment') . '</a> ';
                    endif;
                    $action .= '</div>';
                    $action .= '</div>';
                else :
                    return '...';
                endif;
                return $action;
            })
            ->rawColumns(['date', 'invoice_no', 'branch', 'customer_details', 'payment_status', 'total', 'action'])
            ->make(true);
    }

    public function getPaymentHistory($id)
    {
        $customer          = $this->repo->getFind($id);
        $paymentHistory    = $this->saleRepo->getCustomerSalesPayments($customer->id);

        return DataTables::of($paymentHistory)
            ->addIndexColumn()
            ->editColumn('date', function ($payment) {
                return \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y');
            })
            ->editColumn('invoice_no', function ($payment) {
                return '<a href="' . route('invoice.view', $payment->sale_id) . '" class="text-primary">' . @$payment->sale->invoice_no . '</a>';
            })
            ->editColumn('payment_info', function ($payment) {
                $paymentInfo = '';
                $paymentInfo .= '<div class="d-flex">';
                $paymentInfo .=  '<b>' . __('payment_method') . '</b>:' .  __(\Config::get('pos_default.purchase.payment_method.' . $payment->payment_method));
                $paymentInfo .= '</div>';
                if ($payment->payment_method == \Modules\Purchase\Enums\PaymentMethod::BANK) :
                    $paymentInfo .= '<div class="d-flex">';
                    $paymentInfo .= '<b>' . __('holder_name') . '</b>:' . $payment->bank_holder_name;
                    $paymentInfo .=  '</div>';
                    $paymentInfo .= '<div class="d-flex">';
                    $paymentInfo .= '<b>' . __('account_no') . '</b>:' . $payment->bank_account_no;
                    $paymentInfo .= '</div>';
                endif;
                return $paymentInfo;
            })
            ->editColumn('amount', function ($payment) {
                return @businessCurrency($payment->sale->business_id) . ' ' . @$payment->amount;
            })
            ->editColumn('document', function ($payment) {
                return '<a href="' . @$payment->documents . '">' . __('download') . '</a>';
            })
            ->editColumn('description', function ($payment) {
                return $payment->description;
            })
            ->editColumn('action', function ($payment) {
                $action = '';
                $action .= '<div class="dropdown ">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                $action .= '<a href="' . route('invoice.view', $payment->sale_id) . '" data-title="' . __('view') . '" class="dropdown-item "  ><i class="fa fa-file-invoice-dollar"></i>' . __('invoice') . '</a>';
                $action .=  '</div>';
                $action .=  '</div>';
                return $action;
            })
            ->rawColumns(['date', 'invoice_no', 'payment_info', 'amount', 'document', 'description', 'action'])
            ->make(true);
    }
    // end sale invoice and payment

    // pos invoice and payment
    public function  getPosInvoice($id)
    {
        $customer             = $this->repo->getFind($id);
        $posInvoices          = $this->posRepo->getCustomerPosSales($customer->id);
    
        return DataTables::of($posInvoices)
            ->addIndexColumn()
            ->editColumn('date', function ($pos) {
                return   Carbon::parse($pos->created_at)->format('d-m-Y');
            })
            ->editColumn('invoice_no', function ($pos) { 
                return '<a href="' . route('pos.invoice.view', $pos->id) . '" class="text-primary">' . @$pos->invoice_no . '</a>';
            })
            ->editColumn('branch', function ($pos) {
                return @$pos->branch->name;
            })
            ->editColumn('customer_details', function ($pos) {
                $customerDetails = '';
                $customerDetails .= '<div class="d-flex"> ';
                $customerDetails .= '<span>' . __('type') . '</span>    :' . __(Config::get('pos_default.customer_type.' . @$pos->customer_type));
                $customerDetails .= '</div>';
                if ($pos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER) :
                    $customerDetails .= '<div class="d-flex"> ';
                    $customerDetails .= '<span>' . __('name') . '</span>    :' .  @$pos->customer->name;
                    $customerDetails .= '</div>';
                    $customerDetails .= '<div class="d-flex">';
                    $customerDetails .= '<span>' . __('email') . '</span> :' . @$pos->customer->email;
                    $customerDetails .= '</div>';
                    $customerDetails .= '<div class="d-flex">';
                    $customerDetails .= '<span>' . __('phone') . '</span>  :' . @$pos->customer->phone;
                    $customerDetails .= '</div>';
                    $customerDetails .= '<div class="d-flex">';
                    $customerDetails .=  '<span>' . __('address') . '</span>  : <span class="address">' .  @$pos->customer->address . '</span>';
                    $customerDetails .= '</div> ';
                endif;
                return $customerDetails;
            })
            ->editColumn('payment_status', function ($pos) {
                return @$pos->my_payment_status;
            })
            ->editColumn('total', function ($pos) {
                $total = '';
                $total .= '<span>' . __('total') . ':</span> ' . @businessCurrency($pos->business_id) . ' ' . @number_format($pos->total_sale_price, 2) . '<br/>';
                $total .= '<span class="text-success">' . __('paid') . ':</span>' . @businessCurrency($pos->business_id) . ' ' . @number_format($pos->payments->sum('amount'), 2) . '<br/>';
                $total .= '<span class="text-danger">' . __('due') . ':</span> ' . @businessCurrency($pos->business_id) . ' ' . number_format($pos->DueAmount, 2);
                return $total;
            })
            ->editColumn('action', function ($pos) {
                $action = '';
                if (hasPermission('invoice_view') || hasPermission('pos_read_payment')) :
                    $action .= '<div class="dropdown ">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= '<i class="fa fa-cogs"></i> ';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
                    if (hasPermission('invoice_view')) :
                        $action .= '<a href="' . route('pos.invoice.view', $pos->id) . '" data-title="' . __('view') . '" class="dropdown-item "  ><i class="fa fa-eye"></i>' . __('view') . '</a>';
                    endif;

                    if (hasPermission('pos_read_payment')) :
                        $action .= '<a href="#" data-title="' . __('manage_pos_payment') . '" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="' . route('pos.manage.payment', $pos->id) . '"><i class="fa fa-hand-holding-dollar"></i>' . __('payment') . '</a> ';
                    endif;
                    $action .= '</div>';
                    $action .= '</div>';
                else :
                    return '...';
                endif;
                return $action;
            })
            ->rawColumns(['date', 'invoice_no', 'branch', 'customer_details', 'payment_status', 'total', 'action'])
            ->make(true);
    }

    public function getPosPaymentHistory($id)
    {

        $customer          = $this->repo->getFind($id);
        $paymentHistory    = $this->posRepo->getCustomerPosSalesPayments($customer->id);

        return DataTables::of($paymentHistory)
            ->addIndexColumn()
            ->editColumn('date', function ($payment) {
                return \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y');
            })
            ->editColumn('invoice_no', function ($payment) {
                return '<a href="' . route('pos.invoice.view', $payment->pos_id) . '" class="text-primary">' . @$payment->pos->invoice_no . '</a>';
            })
            ->editColumn('payment_info', function ($payment) {
                $paymentInfo = '';
                $paymentInfo .= '<div class="d-flex">';
                $paymentInfo .=  '<b>' . __('payment_method') . '</b>:' .  __(Config::get('pos_default.purchase.payment_method.' . $payment->payment_method));
                $paymentInfo .= '</div>';
                if ($payment->payment_method == \Modules\Purchase\Enums\PaymentMethod::BANK) :
                    $paymentInfo .= '<div class="d-flex">';
                    $paymentInfo .= '<b>' . __('holder_name') . '</b>:' . $payment->bank_holder_name;
                    $paymentInfo .=  '</div>';
                    $paymentInfo .= '<div class="d-flex">';
                    $paymentInfo .= '<b>' . __('account_no') . '</b>:' . $payment->bank_account_no;
                    $paymentInfo .= '</div>';
                endif;
                return $paymentInfo;
            })
            ->editColumn('amount', function ($payment) {
                return @businessCurrency($payment->pos->business_id) . ' ' . @$payment->amount;
            })
            ->editColumn('document', function ($payment) {
                return '<a href="' . @$payment->documents . '">' . __('download') . '</a>';
            })
            ->editColumn('description', function ($payment) {
                return $payment->description;
            })
            ->editColumn('action', function ($payment) {
                $action = '';
                $action .= '<div class="dropdown ">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                $action .= '<a href="' . route('pos.invoice.view', $payment->pos_id) . '" data-title="' . __('view') . '" class="dropdown-item "  ><i class="fa fa-file-invoice-dollar"></i>' . __('invoice') . '</a>';
                $action .=  '</div>';
                $action .=  '</div>';
                return $action;
            })
            ->rawColumns(['date', 'invoice_no', 'payment_info', 'amount', 'document', 'description', 'action'])
            ->make(true); 

    }
    // end pos invoice and payment

    public function createModal()
    {
        $businesses = $this->businessRepo->getAll();
        return view('customer::create_modal', compact('businesses'));
    }

    public function storeModal(StoreRequest $request)
    {
        if (env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'), __('errors'));
            return redirect()->back()->withInput();
        }

        if ($this->repo->store($request)) :
            Toastr::success(__('customer_store_successfully'),  __('success'));
            return redirect()->back();
        else :
            Toastr::error(__('error'),  __('errors'));
            return redirect()->back();
        endif;
    }

    public function getCustomer(Request $request)
    {
        if ($request->ajax()) :
            return response()->json($this->repo->getFind($request->customer_id));
        endif;
        return '';
    }


    public function getServiceSaleInvoice($id)
    {
        $invoices   = $this->serviceSaleRepo->getCustomerInvoice($id);
        return DataTables::of($invoices)
            ->addIndexColumn()
            ->editColumn('date', function ($sale) {
                return \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y');
            })
            ->editColumn('invoice_no', function ($sale) {
                return ' <a href="' . route('servicesale.invoice.view', $sale->id) . '" class="text-primary">' . @$sale->invoice_no . '</a>';
            })
            ->editColumn('branch', function ($sale) {
                return @$sale->branch->name;
            })
            ->editColumn('customer_details', function ($sale) {
                $customerDetails  = '';
                $customerDetails  .= '<div class="d-flex">';
                $customerDetails  .= '<span>' . __('type') . '</span>    :' . __(\Config::get('pos_default.customer_type.' . @$sale->customer_type));
                $customerDetails  .= ' </div>';
                if ($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER) :
                    $customerDetails  .= '<div class="d-flex"> ';
                    $customerDetails  .= '<span>' . __('name') . '</span>    :' . @$sale->customer->name;
                    $customerDetails  .= '</div>';
                    $customerDetails  .= '<div class="d-flex">';
                    $customerDetails  .= '<span>' . __('email') . '</span> :' . @$sale->customer->email;
                    $customerDetails  .= '</div>';
                    $customerDetails  .= '<div class="d-flex">';
                    $customerDetails  .= '<span>' . __('phone') . '</span>  :' . @$sale->customer->phone;
                    $customerDetails  .= '</div>';
                    $customerDetails  .= '<div class="d-flex">';
                    $customerDetails  .= '<span>' . __('address') . '</span>  :' . '<span class="address"> ' . @$sale->customer->address . '</span>';
                    $customerDetails  .= '</div>';
                endif;

                return $customerDetails;
            })
            ->editColumn('payment_status', function ($sale) {
                return @$sale->my_payment_status;
            })
            ->editColumn('total', function ($sale) {
                $total = '';
                $total .= '<span>' . __('total') . ':</span>' .  @businessCurrency($sale->business_id) . ' ' . @number_format($sale->total_sale_price, 2) . '<br/>';
                $total .= '<span class="text-success">' . __('paid') . ':</span>' . @businessCurrency($sale->business_id) . ' ' . @number_format($sale->payments->sum('amount'), 2) . '<br/>';
                $total .= '<span class="text-danger">' . __('due') . ':</span>' . @businessCurrency($sale->business_id) . ' ' . number_format($sale->DueAmount, 2);
                return $total;
            })
            ->editColumn('action', function ($sale) {
                $action  = '';
                if (hasPermission('invoice_view') || hasPermission('service_sale_read_payment')) :
                    $action  .= '<div class="dropdown ">';
                    $action  .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action  .= ' <i class="fa fa-cogs"></i>';
                    $action  .= '</a>';
                    $action  .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> ';
                    if (hasPermission('invoice_view')) :
                        $action  .= '<a href="' . route('servicesale.invoice.view', $sale->id) . '" data-title="' . __('view') . '" class="dropdown-item "  ><i class="fa fa-eye"></i>' . __('view') . '</a>';
                    endif;

                    if (hasPermission('service_sale_read_payment')) :
                        $action  .= '<a href="#" data-title="' . __('manage_service_sale_payment') . '" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="' . route('servicesale.manage.payment', $sale->id) . '"><i class="fa fa-hand-holding-dollar"></i>' . __('payment') . '</a>';
                    endif;
                    $action  .= '</div>';
                    $action  .= '</div> ';
                else :
                    return '...';
                endif;
                return $action;
            })
            ->rawColumns(['date', 'invoice_no', 'branch', 'customer_details', 'payment_status', 'total', 'action'])
            ->make(true);
    }
    public function getServiceSalePaymentHistory($id)
    {
        $customer          = $this->repo->getFind($id);
        $paymentHistory    = $this->serviceSaleRepo->getCustomerInvoicePayments($customer->id);

        return DataTables::of($paymentHistory)
            ->addIndexColumn()
            ->editColumn('date', function ($payment) {
                return \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y');
            })
            ->editColumn('invoice_no', function ($payment) {
                return '<a href="' . route('servicesale.invoice.view', $payment->sale->id) . '" class="text-primary">' . @$payment->sale->invoice_no . '</a>';
            })
            ->editColumn('payment_info', function ($payment) {
                $paymentInfo = '';
                $paymentInfo .= '<div class="d-flex">';
                $paymentInfo .=  '<b>' . __('payment_method') . '</b>:' .  __(\Config::get('pos_default.purchase.payment_method.' . $payment->payment_method));
                $paymentInfo .= '</div>';
                if ($payment->payment_method == \Modules\Purchase\Enums\PaymentMethod::BANK) :
                    $paymentInfo .= '<div class="d-flex">';
                    $paymentInfo .= '<b>' . __('holder_name') . '</b>:' . $payment->bank_holder_name;
                    $paymentInfo .=  '</div>';
                    $paymentInfo .= '<div class="d-flex">';
                    $paymentInfo .= '<b>' . __('account_no') . '</b>:' . $payment->bank_account_no;
                    $paymentInfo .= '</div>';
                endif;
                return $paymentInfo;
            })
            ->editColumn('amount', function ($payment) {
                return @businessCurrency($payment->sale->business_id) . ' ' . @$payment->amount;
            })
            ->editColumn('document', function ($payment) {
                return '<a href="' . @$payment->documents . '">' . __('download') . '</a>';
            })
            ->editColumn('description', function ($payment) {
                return $payment->description;
            })
            ->editColumn('action', function ($payment) {
                $action = '';
                $action .= '<div class="dropdown ">';
                $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $action .= '<i class="fa fa-cogs"></i>';
                $action .= '</a>';
                $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                $action .= '<a href="' . route('servicesale.invoice.view', $payment->sale->id) . '" data-title="' . __('view') . '" class="dropdown-item "  ><i class="fa fa-file-invoice-dollar"></i>' . __('invoice') . '</a>';
                $action .=  '</div>';
                $action .=  '</div>';
                return $action;
            })
            ->rawColumns(['date', 'invoice_no', 'payment_info', 'amount', 'document', 'description', 'action'])
            ->make(true);
    }
}
