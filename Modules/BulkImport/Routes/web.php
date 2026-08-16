<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\BulkImport\Http\Controllers\BulkExcelImportController;
use Modules\BulkImport\Http\Controllers\BulkImportController;
  
Route::middleware(['XSS','IsInstalled'])->group(function(){
    Route::middleware(['auth','isSubscribed'])->group(function(){
        Route::prefix('bulk-import')
        ->controller(BulkImportController::class)
        ->name('bulk.import.')
        ->group(function(){
            //category
            Route::prefix('category')
            ->name('category.')
            ->group(function(){
                Route::get('/',                 'categoryIndex'     )->name('index')->middleware('hasPermission:category_bulk_import');
                Route::post('/bulk-store',      'CategoryBulkStore')->name('store')->middleware('hasPermission:category_bulk_import');
                //excel import
                Route::controller(BulkExcelImportController::class)->group(function(){
                    Route::get('/excel',            'categoryExcelBulkIndex')->name('excel.index')->middleware('hasPermission:category_bulk_import');
                    Route::post('/bulk-excel-store','CategoryBulkExcelStore')->name('excel.store')->middleware('hasPermission:category_bulk_import');
                });
            });
            
            //brand
            Route::prefix('brand')
            ->name('brand.')
            ->group(function(){ 
                Route::get('/',              'brandIndex'    )->name('index')->middleware('hasPermission:brand_bulk_import');
                Route::post('/bulk-store',   'BrandBulkStore')->name('store')->middleware('hasPermission:brand_bulk_import');
                //excel import
                Route::controller(BulkExcelImportController::class)->group(function(){
                    Route::get('/excel',            'brandExcelBulkIndex')->name('excel.index')->middleware('hasPermission:brand_bulk_import');
                    Route::post('/bulk-excel-store','brandBulkExcelStore')->name('excel.store')->middleware('hasPermission:brand_bulk_import');
                });
            });
            
            //Customer
            Route::prefix('customer')
            ->name('customer.')
            ->group(function(){ 
                Route::get('/',              'customerIndex'    )->name('index')->middleware('hasPermission:customer_bulk_import');
                Route::post('/bulk-store',   'customerBulkStore')->name('store')->middleware('hasPermission:customer_bulk_import');
                //excel import
                Route::controller(BulkExcelImportController::class)->group(function(){
                    Route::get('/excel',            'customerExcelBulkIndex')->name('excel.index')->middleware('hasPermission:customer_bulk_import');
                    Route::post('/bulk-excel-store','customerBulkExcelStore')->name('excel.store')->middleware('hasPermission:customer_bulk_import');
                }); 
            });
             
            //Supplier
            Route::prefix('supplier')
            ->name('supplier.')
            ->group(function(){ 
                Route::get('/',              'supplierIndex'    )->name('index')->middleware('hasPermission:supplier_bulk_import');
                Route::post('/bulk-store',   'supplierBulkStore')->name('store')->middleware('hasPermission:supplier_bulk_import');
                 //excel import
                 Route::controller(BulkExcelImportController::class)->group(function(){
                    Route::get('/excel',            'supplierExcelBulkIndex')->name('excel.index')->middleware('hasPermission:supplier_bulk_import');
                    Route::post('/bulk-excel-store','supplierBulkExcelStore')->name('excel.store')->middleware('hasPermission:supplier_bulk_import');
                });
            });
               
            //Product
            Route::prefix('product')
            ->name('product.')
            ->group(function(){ 
                Route::get('/',                    'productIndex'       )->name('index')->middleware('hasPermission:product_bulk_import');
                Route::post('/bulk-store',         'productBulkStore'   )->name('store')->middleware('hasPermission:product_bulk_import');
                Route::get('get-unit-name',        'getUnitName'        )->name('units.get.names');
                Route::get('get-brand-name',       'getBrandName'       )->name('brand.get.names');
                Route::get('get-warranty-name',    'getWarrantyName'    )->name('warranty.get.names');
                Route::get('get-category-name',    'getCategoryName'    )->name('category.get.names');
                Route::get('get-subcategory-name', 'getSubcategoryName' )->name('subcategory.get.names');
                Route::get('get-branch-name',      'getBranchName'      )->name('branch.get.names');
                Route::get('get-variation-name',   'getVariationName'   )->name('variation.get.names');
                Route::get('get-variation-values', 'getVariationValues' )->name('variation.get.values');
                Route::get('get-taxrate-name',     'getTaxrateName'     )->name('taxrate.get.names'); 

                 //excel import
                 Route::controller(BulkExcelImportController::class)->group(function(){
                    Route::get('/excel',            'productExcelBulkIndex')->name('excel.index')->middleware('hasPermission:product_bulk_import');
                    Route::post('/bulk-excel-store','productBulkExcelStore')->name('excel.store')->middleware('hasPermission:product_bulk_import');
                    Route::post('/get-subcategory', 'getSubcategory')->name('get.subcategory');
                    Route::post('/get-variation-values', 'getVariationValues')->name('get.variation.values');
                });

            }); 
        });
    });
});
 
 