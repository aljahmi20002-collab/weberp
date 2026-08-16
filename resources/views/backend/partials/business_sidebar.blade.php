{{-- service --}}
@if (hasPermission('service_read'))
    <li class="{{ request()->is('services*') ? 'open' : '' }}">
        <a href="{{ route('services.index') }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>{{ __('services') }}</span>
        </a>
        <a href="{{ route('services.index') }}" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="{{ __('services') }}">
            <i class="fa-solid fa-briefcase"></i>
        </a>
    </li>
@endif
{{-- product  --}}
@if (hasPermission('category_read') ||
        hasPermission('brand_read') ||
        hasPermission('warranty_read') ||
        hasPermission('variation_read') ||
        hasPermission('unit_read') ||
        hasPermission('product_read'))
    <li class="{{ request()->is('category*', 'brand*', 'warranty*', 'variation*', 'unit*', 'product*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa-solid fa-cube"></i>
            <span>{{ __('products') }}</span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="fa-solid fa-cube"></i>
        </a>
        <ul @if (request()->is('category*', 'brand*', 'warranty*', 'variation*', 'unit*', 'product*')) style="display:block" @endif>
            @if (hasPermission('product_read'))
                <li class="{{ request()->is('product*') ? 'open' : '' }}">
                    <a href="{{ route('product.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('products') }}</span>
                    </a>
                    <a href="{{ route('product.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('products') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('category_read'))
                <li class="{{ request()->is('category*') ? 'open' : '' }}">
                    <a href="{{ route('category.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('categories') }}</span>
                    </a>
                    <a href="{{ route('category.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('categories') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('brand_read'))
                <li class="{{ request()->is('brand*') ? 'open' : '' }}">
                    <a href="{{ route('brand.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('brands') }}</span>
                    </a>
                    <a href="{{ route('brand.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('brands') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            @if (hasPermission('warranty_read'))
                <li class="{{ request()->is('warranty*') ? 'open' : '' }}">
                    <a href="{{ route('warranty.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('warranties') }}</span>
                    </a>
                    <a href="{{ route('warranty.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('warranties') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('variation_read'))
                <li class="{{ request()->is('variation*') ? 'open' : '' }}">
                    <a href="{{ route('variation.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('variations') }}</span>
                    </a>
                    <a href="{{ route('variation.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('variations') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('unit_read'))
                <li class="{{ request()->is('units*') ? 'open' : '' }}">
                    <a href="{{ route('units.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('units') }}</span>
                    </a>
                    <a href="{{ route('units.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('units') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

        </ul>
    </li>
@endif
{{-- end product  --}}
@if (hasPermission('purchase_read') || hasPermission('purchase_return_read'))
    {{-- Purchase --}}
    <li class="{{ request()->is('purchase*', 'return/purchase*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>{{ __('purchase') }}</span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="fa-solid fa-cart-shopping"></i>
        </a>
        <ul @if (request()->is('purchase*', 'return/purchase*')) style="display:block" @endif>
            @if (hasPermission('purchase_read'))
                <li class="{{ request()->is('purchase*') ? 'open' : '' }}">
                    <a href="{{ route('purchase.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('purchases') }}</span>
                    </a>
                    <a href="{{ route('purchase.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('purchases') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            @if (hasPermission('purchase_return_read'))
                <li class="{{ request()->is('return/purchase*') ? 'open' : '' }}">
                    <a href="{{ route('purchase.return.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('purchases_return') }}</span>
                    </a>
                    <a href="{{ route('purchase.return.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('purchases_return') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </li>
    {{-- End purchase --}}
@endif


@if (hasPermission('sale_read') ||
        hasPermission('pos_read') ||
        hasPermission('sale_proposal_read') ||
        hasPermission('service_sale_read'))
    {{-- Sell --}}
    <li class="{{ request()->is('sale*', 'pos*', 'proposal*', 'service-sale*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa-solid fa-circle-up"></i>
            <span>{{ __('sell') }}</span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="fa-solid fa-circle-up"></i>
        </a>
        <ul @if (request()->is('sale*', 'pos*', 'proposal*', 'service-sale*')) style="display:block" @endif>
            @if (hasPermission('pos_read'))
                <li class="{{ request()->is('pos/list*') ? 'open' : '' }}">
                    <a href="{{ route('pos.list') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('pos_list') }}</span>
                    </a>
                    <a href="{{ route('pos.list') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('pos_list') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('pos_create'))
                <li class="{{ request()->is('pos*') ? 'open' : '' }}">
                    <a href="{{ route('pos.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('pos') }}</span>
                    </a>
                    <a href="{{ route('pos.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('pos') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('sale_read'))
                <li class="{{ request()->is('sale/lis*', 'sale/inv*') ? 'open' : '' }}">
                    <a href="{{ route('sale.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('sale_list') }}</span>
                    </a>
                    <a href="{{ route('sale.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('sale_list') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            @if (hasPermission('sale_create'))
                <li class="{{ request()->is('sale/creat*') ? 'open' : '' }}">
                    <a href="{{ route('sale.create') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('sale_add') }}</span>
                    </a>
                    <a href="{{ route('sale.create') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('sale_add') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('sale_proposal_read'))
                <li class="{{ request()->is('proposal*') ? 'open' : '' }}">
                    <a href="{{ route('saleproposal.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('proposal') }}</span>
                    </a>
                    <a href="{{ route('saleproposal.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('proposal') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            @if (hasPermission('service_sale_read'))
                <li class="{{ request()->is('service-sale*') ? 'open' : '' }}">
                    <a href="{{ route('servicesale.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('service_sale') }}</span>
                    </a>
                    <a href="{{ route('servicesale.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('service_sale') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </li>
    {{-- End Sell --}}
@endif


{{-- Stock Transfer --}}
@if (hasPermission('stock_transfer_read'))
    <li class="{{ request()->is('stock-transfer*') ? 'open' : '' }}">
        <a href="{{ route('stock.transfer.index') }}">
            <i class="fa-solid fa-truck-fast"></i>
            <span>{{ __('stock_transfer') }}</span>
        </a>
        <a href="{{ route('stock.transfer.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
            data-bs-placement="right" data-bs-original-title="{{ __('stock_transfer') }}">
            <i class="fa-solid fa-truck-fast"></i>
        </a>
    </li>
@endif

@if (hasPermission('customer_read') || hasPermission('supplier_read'))
    <li class="{{ request()->is('customers*', 'supplier*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa-regular fa-address-book"></i>
            <span>{{ __('contacts') }}</span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="fa-regular fa-address-book"></i>
        </a>
        <ul @if (request()->is('customers*', 'supplier*')) style="display:block" @endif>
            @if (hasPermission('customer_read'))
                <li class="{{ request()->is('customer*') ? 'open' : '' }}">
                    <a href="{{ route('customers.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('customer') }}</span>
                    </a>
                    <a href="{{ route('customers.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('customer') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            @if (hasPermission('supplier_read'))
                <li class="{{ request()->is('supplier*') ? 'open' : '' }}">
                    <a href="{{ route('suppliers.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('suppliers') }}</span>
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('suppliers') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Accounts --}}
@if (hasPermission('account_read'))
    <li class="{{ request()->is('accounts*', 'invoice*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa-solid fa-money-check-dollar"></i>
            <span>{{ __('accounts') }}</span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="fa-solid fa-money-check-dollar"></i>
        </a>
        <ul @if (request()->is('accounts*', 'invoice*')) style="display:block" @endif>

            @if (hasPermission('account_read'))
                <li class="{{ request()->is('accounts/account*') ? 'open' : '' }}">
                    <a href="{{ route('accounts.account.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('account') }}</span>
                    </a>
                    <a href="{{ route('accounts.account.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('account') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('fund_transfer_read'))
                <li class="{{ request()->is('accounts/fund-transfer*') ? 'open' : '' }}">
                    <a href="{{ route('accounts.fund.transfer.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('fund_transfer') }}</span>
                    </a>
                    <a href="{{ route('accounts.fund.transfer.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('fund_transfer') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('bank_transaction_read'))
                <li class="{{ request()->is('accounts/bank-transaction*') ? 'open' : '' }}">
                    <a href="{{ route('accounts.account.bank.transaction') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('bank_transaction') }}</span>
                    </a>
                    <a href="{{ route('accounts.account.bank.transaction') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('bank_transaction') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('income_read'))
                <li class="{{ request()->is('accounts/income*') ? 'open' : '' }}">
                    <a href="{{ route('accounts.income.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('income') }}</span>
                    </a>
                    <a href="{{ route('accounts.income.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('income') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('expense_read'))
                <li class="{{ request()->is('accounts/expense*') ? 'open' : '' }}">
                    <a href="{{ route('accounts.expense.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('expense') }}</span>
                    </a>
                    <a href="{{ route('accounts.expense.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('expense') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            @if (hasPermission('invoice_read'))
                <li class="{{ request()->is('invoice*') ? 'open' : '' }}">
                    <a href="{{ route('invoice.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('invoice') }}</span>
                    </a>
                    <a href="{{ route('invoice.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="{{ __('invoice') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
{{-- End Accounts --}}


{{-- bulk import --}}
@if (hasPermission('category_bulk_import') ||
        hasPermission('brand_bulk_import') ||
        hasPermission('customer_bulk_import') ||
        hasPermission('supplier_bulk_import') ||
        hasPermission('product_bulk_import'))
    <li class="{{ request()->is('bulk-import*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa fa-users"></i>
            <span>{{ __('bulk_import') }} </span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="far fa-file-alt"></i>
        </a>
        <ul @if (request()->is('bulk-import*')) style="display:block" @endif>

            {{-- category bulk import --}}
            @if (hasPermission('category_bulk_import'))
                <li class="{{ request()->is('bulk-import/category*') ? 'open' : '' }}">
                    <a href="{{ route('bulk.import.category.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('category') }}</span>
                    </a>
                    <a href="{{ route('bulk.import.category.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('category') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- category bulk import --}}

            {{-- brand bulk import --}}
            @if (hasPermission('brand_bulk_import'))
                <li class="{{ request()->is('bulk-import/brand*') ? 'open' : '' }}">
                    <a href="{{ route('bulk.import.brand.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('brand') }}</span>
                    </a>
                    <a href="{{ route('bulk.import.brand.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('brand') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- brand bulk import --}}


            {{-- customer bulk import --}}
            @if (hasPermission('customer_bulk_import'))
                <li class="{{ request()->is('bulk-import/customer*') ? 'open' : '' }}">
                    <a href="{{ route('bulk.import.customer.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('customer') }}</span>
                    </a>
                    <a href="{{ route('bulk.import.customer.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('customer') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- customer bulk import --}}

            {{-- suppliers bulk import --}}
            @if (hasPermission('supplier_bulk_import'))
                <li class="{{ request()->is('bulk-import/supplier*') ? 'open' : '' }}">
                    <a href="{{ route('bulk.import.supplier.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('supplier') }}</span>
                    </a>
                    <a href="{{ route('bulk.import.supplier.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('supplier') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- suppliers bulk import --}}


            {{-- Products bulk import --}}
            @if (hasPermission('product_bulk_import'))
                <li class="{{ request()->is('bulk-import/product*') ? 'open' : '' }}">
                    <a href="{{ route('bulk.import.product.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('product') }}</span>
                    </a>
                    <a href="{{ route('bulk.import.product.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('product') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- Products bulk import --}}

        </ul>
    </li>
@endif



@if (hasPermission('project_read'))
    <li class="{{ request()->is('project*') ? 'open' : '' }}">
        <a href="{{ route('project.index') }}">
            <i class="fa-solid fa-diagram-project"></i>
            <span>{{ __('project') }}</span>
        </a>
        <a href="{{ route('project.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
            data-bs-placement="right" data-bs-original-title="project">
            <i class="fa-solid fa-diagram-project"></i>
        </a>
    </li>
@endif



@if (hasPermission('todo_read'))
    <li class="{{ request()->is('todo*') ? 'open' : '' }}">
        <a href="{{ route('todoList.index') }}">
            <i class="fas fa-list-alt"></i>
            <span>{{ __('todo_list') }}</span>
        </a>
        <a href="{{ route('todoList.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
            data-bs-placement="right" data-bs-original-title="TodoList">
            <i class="fas fa-list-alt"></i>
        </a>
    </li>
@endif
@if (hasPermission('assets_read'))
    <li class="{{ request()->is('assets*') ? 'open' : '' }}">
        <a href="{{ route('assets.index') }}">
            <i class="fas fa-list-alt"></i>
            <span>{{ __('assets') }}</span>
        </a>
        <a href="{{ route('assets.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
            data-bs-placement="right" data-bs-original-title="{{ __('assets') }}">
            <i class="fas fa-list-alt"></i>
        </a>
    </li>
@endif


{{-- business ubscription --}}
@if (business() || isUser())
    <li class="{{ request()->is('business-subscription*') ? 'open' : '' }}">
        <a href="{{ route('business.subscription.index') }}">
            <i class="fa-regular fa-money-bill-1"></i>
            <span>{{ __('subscription') }}</span>
        </a>
        <a href="{{ route('business.subscription.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
            data-bs-placement="right" data-bs-original-title="{{ __('subscription') }}">
            <i class="fa-regular fa-money-bill-1"></i>
        </a>
    </li>
@endif


{{-- hrm --}}
@if (hasPermission('leave_type_read') ||
        hasPermission('designation_read') ||
        hasPermission('department_read') ||
        hasPermission('leave_assign_read') ||
        hasPermission('apply_leave_read') ||
        hasPermission('leave_request_read'))
    <li class="{{ request()->is('hrm*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa fa-users"></i>
            <span>{{ __('hrm') }} </span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="far fa-file-alt"></i>
        </a>
        <ul @if (request()->is('hrm*')) style="display:block" @endif>


            {{-- Designation --}}
            @if (hasPermission('designation_read'))
                <li class="{{ request()->is('hrm/designation*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.designation.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('designation') }}</span>
                    </a>
                    <a href="{{ route('hrm.designation.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="designation">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- end designation --}}


            {{-- Department --}}
            @if (hasPermission('department_read'))
                <li class="{{ request()->is('hrm/department*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.department.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('department') }}</span>
                    </a>
                    <a href="{{ route('hrm.department.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="department">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- end Department --}}


            {{-- leave type --}}
            @if (hasPermission('leave_type_read'))
                <li class="{{ request()->is('hrm/leave-type*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.leave.type.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('leave_type') }}</span>
                    </a>
                    <a href="{{ route('hrm.leave.type.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="leave_type">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- end leave type --}}


            {{-- leave Assign --}}
            @if (hasPermission('leave_assign_read'))
                <li class="{{ request()->is('hrm/leave-assign*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.leave.assign.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('leave_assign') }}</span>
                    </a>
                    <a href="{{ route('hrm.leave.assign.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="leave_assign">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- end leave Assign --}}

            {{-- available leave --}}
            @if (hasPermission('available_leave_read'))
                <li class="{{ request()->is('hrm/available-leave*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.available.leave.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('available_leave') }}</span>
                    </a>
                    <a href="{{ route('hrm.available.leave.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="apply_leave">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- end available leave --}}

            {{-- Apply leave --}}
            @if (hasPermission('apply_leave_read'))
                <li class="{{ request()->is('hrm/apply-leave*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.apply.leave.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('apply_leave') }}</span>
                    </a>
                    <a href="{{ route('hrm.apply.leave.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="apply_leave">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- end Apply leave --}}

            {{-- Apply request --}}
            @if (hasPermission('leave_request_read'))
                <li class="{{ request()->is('hrm/leave-request*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.leave.request.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('leave_request') }}</span>
                    </a>
                    <a href="{{ route('hrm.leave.request.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="leave_request">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
            {{-- end Apply request --}}

            @if (hasPermission('weekend_read') ||
                    hasPermission('holiday_read') ||
                    hasPermission('duty_schedule_read') ||
                    hasPermission('attendance_read'))
                {{-- attendance --}}
                <li
                    class="{{ request()->is('hrm/attendance*', 'hrm/attendance/duty-schedule*', 'hrm/employee-attendance', 'hrm/employee-attendance/manage*') ? 'parent-menu open' : 'parent-menu' }}">
                    <a href="#0">
                        <i class="fa fa-users"></i>
                        <span>{{ __('attendance') }} </span>
                    </a>
                    <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="Pages">
                        <i class="far fa-file-alt"></i>
                    </a>
                    <ul @if (request()->is(
                            'hrm/attendance*',
                            'hrm/attendance/duty-schedule*',
                            'hrm/employee-attendance',
                            'hrm/employee-attendance/manage*')) style="display:block" @endif>
                        {{-- weekend --}}
                        @if (hasPermission('weekend_read'))
                            <li class="{{ request()->is('hrm/attendance/weekend*') ? 'open' : '' }}">
                                <a href="{{ route('hrm.attendance.weekend.index') }}">
                                    <i class="fas fa-angles-right"></i>
                                    <span>{{ __('weekends') }}</span>
                                </a>
                                <a href="{{ route('hrm.attendance.weekend.index') }}" class="collapsed-icon"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-original-title="weekend">
                                    <i class="fas fa-angles-right"></i>
                                </a>
                            </li>
                        @endif
                        {{-- end weekend --}}
                        {{-- holiday --}}
                        @if (hasPermission('holiday_read'))
                            <li class="{{ request()->is('hrm/attendance/holiday*') ? 'open' : '' }}">
                                <a href="{{ route('hrm.attendance.holiday.index') }}">
                                    <i class="fas fa-angles-right"></i>
                                    <span>{{ __('holidays') }}</span>
                                </a>
                                <a href="{{ route('hrm.attendance.holiday.index') }}" class="collapsed-icon"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-original-title="holiday">
                                    <i class="fas fa-angles-right"></i>
                                </a>
                            </li>
                        @endif
                        {{-- end holiday --}}

                        {{-- duty schedule --}}
                        @if (hasPermission('duty_schedule_read'))
                            <li class="{{ request()->is('hrm/attendance/duty-schedule*') ? 'open' : '' }}">
                                <a href="{{ route('hrm.attendance.duty.schedule.index') }}">
                                    <i class="fas fa-angles-right"></i>
                                    <span>{{ __('duty_schedule') }}</span>
                                </a>
                                <a href="{{ route('hrm.attendance.duty.schedule.index') }}" class="collapsed-icon"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-original-title="duty_schedule">
                                    <i class="fas fa-angles-right"></i>
                                </a>
                            </li>
                        @endif
                        {{-- end duty schedule --}}

                        {{-- Attendance --}}
                        @if (hasPermission('attendance_read'))
                            <li class="{{ request()->is('hrm/employee-attendance*') ? 'open' : '' }}">
                                <a href="{{ route('hrm.attendance.index') }}">
                                    <i class="fas fa-angles-right"></i>
                                    <span>{{ __('attendance') }}</span>
                                </a>
                                <a href="{{ route('hrm.attendance.index') }}" class="collapsed-icon"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-original-title="attendance">
                                    <i class="fas fa-angles-right"></i>
                                </a>
                            </li>
                        @endif
                        {{-- end Attendace  --}}
                    </ul>
                </li>
                {{-- end attendance --}}
            @endif
        </ul>
    </li>
@endif
{{-- end hrm --}}

{{-- reports --}}
@if (hasPermission('attendance_reports') ||
        hasPermission('profit_loss_reports') ||
        hasPermission('product_wise_profit_reports') ||
        hasPermission('product_wise_pos_profit_reports') ||
        hasPermission('expense_report') ||
        hasPermission('stock_report') ||
        hasPermission('customer_sale_report') ||
        hasPermission('service_sale_report'))
    <li class="{{ request()->is('reports*') ? 'open' : '' }}">
        <a href="#0">
            <i class="fa-solid fa-chart-area"></i>
            <span>{{ __('reports') }}</span>
        </a>
        <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="Pages">
            <i class="fa-solid fa-chart-area"></i>
        </a>
        <ul @if (request()->is('reports*')) style="display:block" @endif>

            @if (hasPermission('attendance_reports'))
                <li class="{{ request()->is('reports/attendance*') ? 'open' : '' }}">
                    <a href="{{ route('reports.attendance.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('attendance_reports') }}</span>
                    </a>
                    <a href="{{ route('reports.attendance.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('attendance_reports') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('profit_loss_reports'))
                <li class="{{ request()->is('reports/profit-loss*') ? 'open' : '' }}">
                    <a href="{{ route('reports.profit.loss.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('profit_loss') }}</span>
                    </a>
                    <a href="{{ route('reports.profit.loss.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('profit_loss') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('product_wise_profit_reports'))
                <li class="{{ request()->is('reports/product-wise-profit*') ? 'open' : '' }}">
                    <a href="{{ route('reports.product.wise.profit.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('product_wise_sale_profit') }}</span>
                    </a>
                    <a href="{{ route('reports.product.wise.profit.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('product_wise_sale_profit') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('product_wise_pos_profit_reports'))
                <li class="{{ request()->is('reports/product-wise-pos-profit*') ? 'open' : '' }}">
                    <a href="{{ route('reports.product.wise.pos.profit.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('product_wise_pos_profit') }}</span>
                    </a>
                    <a href="{{ route('reports.product.wise.pos.profit.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('product_wise_pos_profit') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('expense_report'))
                <li class="{{ request()->is('reports/expense-report*') ? 'open' : '' }}">
                    <a href="{{ route('reports.expense.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('expense_report') }}</span>
                    </a>
                    <a href="{{ route('reports.expense.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('expense_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('stock_report'))
                <li class="{{ request()->is('reports/stock-report*') ? 'open' : '' }}">
                    <a href="{{ route('reports.stock.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span>{{ __('stock_report') }}</span>
                    </a>
                    <a href="{{ route('reports.stock.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('stock_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif


            @if (hasPermission('customer_sale_report'))
                <li class="{{ request()->is('reports/customer-sale-report*') ? 'open' : '' }}">
                    <a href="{{ route('reports.customer.sale.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('customer_sale_report') }}</small></span>
                    </a>
                    <a href="{{ route('reports.customer.sale.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('customer_sale_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif


            @if (hasPermission('customer_pos_report'))
                <li class="{{ request()->is('reports/customer-pos-report*') ? 'open' : '' }}">
                    <a href="{{ route('reports.customer.pos.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('customer_pos_report') }}</small></span>
                    </a>
                    <a href="{{ route('reports.customer.pos.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('customer_pos_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('purchase_report'))
                <li class="{{ request()->is('reports/purchase-report*') ? 'open' : '' }}">
                    <a href="{{ route('reports.purchase.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('purchase_report') }}</small></span>
                    </a>
                    <a href="{{ route('reports.purchase.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('purchase_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('purchase_return_report'))
                <li class="{{ request()->is('reports/purchase-return-report*') ? 'open' : '' }}">
                    <a href="{{ route('reports.purchase.return.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('purchase_return') }}</small></span>
                    </a>
                    <a href="{{ route('reports.purchase.return.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('purchase_return') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('sale_report'))
                <li class="{{ request()->is('reports/report-sale*') ? 'open' : '' }}">
                    <a href="{{ route('reports.sale.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('sale_report') }}</small></span>
                    </a>
                    <a href="{{ route('reports.sale.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('sale_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('pos_report'))
                <li class="{{ request()->is('reports/report-pos*') ? 'open' : '' }}">
                    <a href="{{ route('reports.pos.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('pos_report') }}</small></span>
                    </a>
                    <a href="{{ route('reports.pos.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('pos_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif


            @if (hasPermission('service_sale_report'))
                <li class="{{ request()->is('reports/report-service-sale*') ? 'open' : '' }}">
                    <a href="{{ route('reports.servicesale.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('service_sale_report') }}</small></span>
                    </a>
                    <a href="{{ route('reports.servicesale.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('service_sale_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif

            @if (hasPermission('supplier_report'))
                <li class="{{ request()->is('reports/report-supplier*') ? 'open' : '' }}">
                    <a href="{{ route('reports.supplier.report.index') }}">
                        <i class="fas fa-angles-right"></i>
                        <span><small>{{ __('supplier_report') }}</small></span>
                    </a>
                    <a href="{{ route('reports.supplier.report.index') }}" class="collapsed-icon"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="{{ __('supplier_report') }}">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif


{{-- business support --}}
@if (business() || isUser())
    @if (hasPermission('support_read'))
        <li class="{{ request()->is('support*') ? 'open' : '' }}">
            <a href="{{ route('support.index') }}">
                @if (business())
                    <i class="fa-solid fa-paper-plane"></i>
                    <span> {{ __('support') }} </span>
                @else
                    <i class="fa-solid fa-headset"></i>
                    <span>{{ __('need_help') }}</span>
                @endif
            </a>
            <a href="{{ route('support.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                data-bs-placement="right"
                data-bs-original-title=" @if (business()) {{ __('support') }}
                        @else
                            {{ __('need_help') }} @endif">
                @if (business())
                    <i class="fa-solid fa-paper-plane"></i>
                @else
                    <i class="fa-solid fa-headset"></i>
                @endif
            </a>
        </li>
    @endif
@endif
