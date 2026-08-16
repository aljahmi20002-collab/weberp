<?php

use App\Enums\AccountHead;
use App\Enums\BarcodeType;
use App\Enums\StatementType;
use App\Enums\UserType;
use Modules\Account\Enums\AccountType;
use Modules\Account\Enums\PaymentGateway;
use Modules\Customer\Enums\CustomerType;
use Modules\Purchase\Enums\PaymentMethod;
use Modules\Purchase\Enums\PurchasePayStatus;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Sell\Enums\ShippingStatus;
use Modules\StockTransfer\Enums\StockTransferStatus;

return [

    'user_type' =>[
        'superadmin'=>UserType::SUPERADMIN,
        'admin'     =>UserType::ADMIN,
        'user'      =>UserType::USER
    ],

    'customer_type'=>[
        CustomerType::WALK_CUSTOMER         => 'walk_customer',
        CustomerType::EXISTING_CUSTOMER     => 'existing_customer',
    ],
    'barcode_types'=>[
        BarcodeType::C128  =>  'C128' ,
        BarcodeType::C39   =>  'C39',
        BarcodeType::EAN13 =>  'EAN13' ,
        BarcodeType::EAN8  =>  'EAN8',
        BarcodeType::UPCA  =>  'UPCA' ,
        BarcodeType::UPCE  =>  'UPCE'
    ],

    'label_settings'=>[
        'per_seet_20' =>[
            'name'          => '20 Labels Per Seet',
            'paper_width'   => '8.5',
            'paper_height'  => '11',
            'label_width'   => '4',
            'label_height'  => '1', 

            "paper_width_type"  => 'in',
            "paper_height_type" => 'in',
            "label_width_type"  => 'in',
            "label_height_type" => 'in',

            'label_in_per_row'  => 2, 
            'is'                => 'default_page', 

        ], 
        'per_seet_40' =>[
            'name'          => '40 Labels Per Seet ',
            'paper_width'   => '8.5',
            'paper_height'  => '11',
            'label_width'   => '2',
            'label_height'  => '1', 

            "paper_width_type"  => 'in',
            "paper_height_type" => 'in',
            "label_width_type"  => 'in',
            "label_height_type" => 'in',

            'label_in_per_row' => 4, 
            'is'            => 'default_page', 

        ], 
        'per_seet_50' =>[
            'name'          => '50 Labels Per Seet ',
            'paper_width'   => '8.5',
            'paper_height'  => '11',
            'label_width'   => '1.5',
            'label_height'  => '1',

            "paper_width_type"  => 'in',
            "paper_height_type" => 'in',
            "label_width_type"  => 'in',
            "label_height_type" => 'in',

            'label_in_per_row' => 5, 
            'is'            => 'default_page', 
        ], 
        'continuous_rolls' =>[
            // 'name'            => 'Continuous Rolls , Sheet Size:31.75mm x 25.4mm, Label Size:31.75mm x 25.4mm',
            'name'            => 'Continuous Rolls',
            'paper_width'     => '31.75',
            'paper_height'    => '25.4',
            'label_width'     => '31.75',
            'label_height'    => '25.4',

            "paper_width_type"  => 'mm',
            "paper_height_type" => 'mm',
            "label_width_type"  => 'mm',
            "label_height_type" => 'mm',

            'label_in_per_row'   => 1, 
            'is'              => 'rolls'
            // 'gap'           => '3.18mm'
        ],
        'rongta_series_printer' =>[
            // 'name'            => 'Rongta 80mm Series Printer ,Sheet Size:65mm x 65mm, Label Size:65mm x 65mm',
            'name'            => 'Rongta 80mm Series Printer',
            'paper_width'     => '65',
            'paper_height'    => '65',
            'label_width'     => '65',
            'label_height'    => '65',

            "paper_width_type"  => 'mm',
            "paper_height_type" => 'mm',
            "label_width_type"  => 'mm',
            "label_height_type" => 'mm',

            'label_in_per_row'   => 1, 
            'is'              => 'rongta'
            // 'gap'           => '3.18mm'
        ],
    ],
    'purchase'=>[
        'payment_status'=>[
            PurchasePayStatus::PAID    => 'paid',
            PurchasePayStatus::PARTIAL => 'partial',
            PurchasePayStatus::DUE     => 'due',
        ],
        'purchase_status'=>[
            PurchaseStatus::PENDING => 'pending',
            PurchaseStatus::ORDERED => 'ordered',
            PurchaseStatus::RECEIVED=> 'received'
        ],
        'payment_method'=>[
            PaymentMethod::CASH   => 'cash',
            PaymentMethod::BANK   => 'bank'
        ]
    ],
    'stock_transfer_status'=>[
        StockTransferStatus::PENDING    => 'pending',
        StockTransferStatus::IN_TRANSIT => 'in_transit',
        StockTransferStatus::COMPLETED  => 'completed'
    ],

    'account_types'=>[
        AccountType::ADMIN      => 'admin',
        AccountType::BRANCH     => 'branch',
    ],
    'payment_gatway'=>[
        PaymentGateway::CASH     => 'cash',
        PaymentGateway::BANK     => 'bank',
        PaymentGateway::MOBILE   => 'mobile'
    ],
  
    'statement_type'=>[
        StatementType::INCOME  => 'income',
        StatementType::EXPENSE => 'expense'
    ],

    'shpping_status' =>[
        ShippingStatus::ORDERED   => 'ordered',
        ShippingStatus::PACKED    => 'packed',
        ShippingStatus::SHIPPED   => 'shipped',
        ShippingStatus::DELIVERED => 'delivered',
        ShippingStatus::CANCELLED => 'cancelled'
    ],

    'priority' =>[
        'low'     => 'low',
        'medium'  => 'medium',
        'high'    => 'high'
    ]
   
];
