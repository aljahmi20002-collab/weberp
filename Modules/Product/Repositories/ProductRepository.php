<?php

namespace Modules\Product\Repositories;

use App\Enums\ImageSize;
use App\Enums\Status;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Business\Entities\Business;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariation;
use Modules\Product\Entities\VariationLocationDetails;
use Modules\TaxRate\Repositories\TaxRateInterface;

class ProductRepository implements ProductInterface
{
    protected $model, $uploadRepo, $taxRepo;
    public function __construct(Product $model, UploadInterface $uploadRepo, TaxRateInterface $taxRepo)
    {
        $this->model       = $model;
        $this->uploadRepo  = $uploadRepo;
        $this->taxRepo     = $taxRepo;
    }
    public function get()
    {
        return $this->model::with([
            'branchs', 'upload',
            'brand', 'category',
            'subcategory', 'warranty',
            'productVariations',
            'productVariations.product',
            'ProductVariationLocations.product',
            'VariationLocations.product',
            'availableQuantity',
            'availableQuantity.product'
        ])->where(function ($query) {
            if (business()) :
                $query->where('business_id', business_id());
            elseif (isUser()) :
                $query->where(['business_id' => business_id()]);
                $query->whereHas('VariationLocations', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                });
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    public function getAllProducts()
    {
        return $this->model::with([
            'branchs', 'upload',
            'brand', 'category',
            'subcategory', 'warranty',
            'productVariations',
            'productVariations.product',
            'ProductVariationLocations.product',
            'VariationLocations.product',
            'availableQuantity',
            'availableQuantity.product'
        ])->where(function ($query) {
            if (business()) :
                $query->where('business_id', business_id());
            elseif (isUser()) :
                $query->where(['business_id' => business_id()]);
                $query->whereHas('VariationLocations', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                });
            endif;
        })->orderByDesc('id')->get();
    }

    public function getCategoryWiseProducts($category_id)
    {
        return $this->model::with([
            'branchs', 'upload',
            'brand', 'category',
            'subcategory', 'warranty',
            'productVariations',
            'productVariations.product',
            'ProductVariationLocations.product',
            'VariationLocations.product',
            'availableQuantity',
            'availableQuantity.product'
        ])->where(function ($query) use ($category_id) {
            if (business()) :
                $query->where('business_id', business_id());
            elseif (isUser()) :
                $query->where(['business_id' => business_id()]);
                $query->whereHas('VariationLocations', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                });
            endif;
            $query->where('category_id', $category_id);
        })->orderByDesc('id')->get();
    }
    public function getSubCategoryWiseProducts($category_id, $subcategory_id)
    {
        return $this->model::with([
            'branchs', 'upload',
            'brand', 'category',
            'subcategory', 'warranty',
            'productVariations',
            'productVariations.product',
            'ProductVariationLocations.product',
            'VariationLocations.product',
            'availableQuantity',
            'availableQuantity.product'
        ])->where(function ($query) use ($category_id, $subcategory_id) {
            if (business()) :
                $query->where('business_id', business_id());
            elseif (isUser()) :
                $query->where(['business_id' => business_id()]);
                $query->whereHas('VariationLocations', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                });
            endif;
            $query->where('category_id', $category_id);
            $query->where('subcategory_id', $subcategory_id);
        })->orderByDesc('id')->get();
    }

    public function branchWiseProducts($branch_id)
    {
        return $this->model::with([
            'branchs', 'upload',
            'brand', 'category',
            'subcategory', 'warranty',
            'productVariations',
            'productVariations.product',
            'ProductVariationLocations.product',
            'VariationLocations.product',
            'availableQuantity',
            'availableQuantity.product'
        ])->where(function ($query) use ($branch_id) {
            if (business()) :
                $query->where('business_id', business_id());
            elseif (isUser()) :
                $query->where(['business_id' => business_id()]);
                $query->whereHas('VariationLocations', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                });
            endif;
            $query->whereHas('VariationLocations', function ($query) use ($branch_id) {
                $query->where('branch_id', $branch_id);
            });
        })->orderByDesc('id')->get();
    }

    public function skuGenerate()
    {
        return random_int(100000, 999999);
    }
    public function getFind($id)
    {
        return $this->model::with([
            'branchs', 'upload',
            'brand', 'category',
            'subcategory', 'warranty',
            'productVariations',
            'productVariations.variation',
            'productVariations.product',
            'ProductVariationLocations.product',
            'VariationLocations.product',
            'availableQuantity',
            'availableQuantity.product'
        ])->find($id);
    }
    public function store($request)
    {
        try {

            if (isUser()) :
                $request['branches'] = [Auth::user()->branch_id];
            endif;
            if (isSuperadmin()) :
                $business_id   = $request->business_id;
            else :
                $business_id   = business_id();
            endif;
            $business               =  Business::find($business_id);
            $product                = new $this->model();
            $product->business_id   = $business_id;
            $product->name          = $request->name;
            if (!blank($business->sku_prefix)) :
                $sku = $business->sku_prefix . '-' . $this->skuGenerate();
            else :
                $sku = $this->skuGenerate();
            endif;
            $product->sku           = $sku;
            $product->unit_id       = $request->unit_id;
            $product->brand_id      = $request->brand_id;
            $product->category_id   = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->tax_id        = $request->tax_id;
            $product->warranty_id   = $request->warranty_id;
            $product->barcode_type  = $request->barcode_type;
            if ($request->enable_stock) :
                $product->enable_stock   = $request->enable_stock == 'on' ? Status::ACTIVE : Status::INACTIVE;
                $product->alert_quantity = $request->alert_quantity;
            endif;
            $product->default_quantity   = $request->quantity;
            $product->variation_id   = $request->variation_id;
            if ($request->image_link) :
                $product->image_id       =  $this->uploadRepo->linktoImageUpload('product', $request->image_link);
            elseif ($request->product_image) :
                $product->image_id       = $this->uploadRepo->uploadImage($request->product_image, 'product/', [ImageSize::PRODUCT_IMAGE_ONE, ImageSize::PRODUCT_IMAGE_TWO, ImageSize::PRODUCT_IMAGE_THREE], '');
            endif;
            $product->purchase_price = $request->default_purchase_price;
            $product->sell_price     = $request->selling_price;
            $product->description    = $request->description;
            $product->created_by     = Auth::user()->id;
            $product->save();

            foreach ($request->variation_values as $key => $variationValue) {
                $sub_sku = $product->sku . random_int(100, 999);
                $productVariation  = $this->productVariation($request, $product, $variationValue, $sub_sku);
                foreach ($request->branches as $branch_id) {
                    $this->VariationLocation($request, $product, $productVariation, $branch_id, $business_id);
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function productvariation($request, $product, $variationValue, $sub_sku)
    { //.product variations
        $productVariation               = new ProductVariation();
        $productVariation->sub_sku      = $sub_sku;
        $productVariation->name         = $variationValue;
        $productVariation->product_id   = $product->id;
        $productVariation->variation_id = $request->variation_id;
        $productVariation->default_purchase_price = $request->default_purchase_price;
        $productVariation->profit_percent         = $request->profit_percent;
        $productVariation->default_sell_price     = $request->selling_price;

        $tax                 = $this->taxRepo->getFind($request->tax_id);
        if ($request->selling_price != 0) :
            $tax_amount          = ($request->selling_price / 100) * $tax->tax_rate;
        else :
            $tax_amount      = 0;
        endif;
        $sell_price_inc_tax  = ($request->selling_price + $tax_amount);
        $productVariation->sell_price_inc_tax = $sell_price_inc_tax;
        $productVariation->save();
        return $productVariation;
    }

    public function VariationLocation($request, $product, $productVariation, $branch_id, $business_id)
    { //variation location details
        $variationLocation               = new VariationLocationDetails();
        $variationLocation->business_id  = $business_id;
        $variationLocation->branch_id    = $branch_id;
        $variationLocation->product_id   = $product->id;
        $variationLocation->variation_id = $request->variation_id;
        $variationLocation->product_variation_id = $productVariation->id;
        $variationLocation->qty_available = $request->quantity == '' ? 0 : $request->quantity;
        $variationLocation->save();
    }

    public function update($id, $request)
    {
        try {

            if (isUser()) :
                $request['branches'] = [Auth::user()->branch_id];
            endif;
            if (isSuperadmin()) :
                $business_id   = $request->business_id;
            else :
                $business_id   = business_id();
            endif;
            $product                = $this->model::find($id);
            $product->business_id   = $business_id;
            $product->name          = $request->name;
            $product->unit_id       = $request->unit_id;
            $product->brand_id      = $request->brand_id;
            $product->category_id   = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->tax_id        = $request->tax_id;
            $product->warranty_id   = $request->warranty_id;
            $product->barcode_type  = $request->barcode_type;
            $product->default_quantity   = $request->quantity;
            if ($request->enable_stock) :
                $product->enable_stock   = $request->enable_stock == 'on' ? Status::ACTIVE : Status::INACTIVE;
                $product->alert_quantity = $request->alert_quantity;
            endif;
            $product->variation_id   = $request->variation_id;
            if ($request->product_image) :
                $product->image_id       =  $this->uploadRepo->uploadImage($request->product_image, 'product/', [ImageSize::PRODUCT_IMAGE_ONE, ImageSize::PRODUCT_IMAGE_TWO, ImageSize::PRODUCT_IMAGE_THREE], $product->image_id);
           
            endif;
  
            $product->purchase_price = $request->default_purchase_price;
            $product->sell_price     = $request->selling_price;
            $product->description    = $request->description;
            $product->created_by     = Auth::user()->id;
            $product->save();

            $destroyData      = ProductVariation::where('product_id', $product->id)->delete();
            $destroyVLocation = VariationLocationDetails::where('product_id', $product->id)->delete();
            foreach ($request->variation_values as $key => $variationValue) {
                $sub_sku = $product->sku . random_int(100, 999);
                $productVariation  = $this->updateProductvariation($request, $product, $variationValue, $sub_sku);
                foreach ($request->branches as $branch_id) {
                    $this->updateVariationLocation($request, $product, $productVariation, $branch_id, $business_id);
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function updateProductvariation($request, $product, $variationValue, $sub_sku)
    { //.product variations

        $productVariation               = new ProductVariation();
        $productVariation->sub_sku      = $sub_sku;
        $productVariation->name         = $variationValue;
        $productVariation->product_id   = $product->id;
        $productVariation->variation_id = $request->variation_id;
        $productVariation->default_purchase_price = $request->default_purchase_price;
        $productVariation->profit_percent         = $request->profit_percent;
        $productVariation->default_sell_price     = $request->selling_price;

        $tax                 = $this->taxRepo->getFind($request->tax_id);
        if ($request->selling_price != 0) :
            $tax_amount          = ($request->selling_price / 100) * $tax->tax_rate;
        else :
            $tax_amount      = 0;
        endif;
        $sell_price_inc_tax  = ($request->selling_price + $tax_amount);
        $productVariation->sell_price_inc_tax = $sell_price_inc_tax;
        $productVariation->save();
        return $productVariation;
    }

    public function updateVariationLocation($request, $product, $productVariation, $branch_id, $business_id)
    { //variation location details
        $variationLocation               = new VariationLocationDetails();
        $variationLocation->business_id  = $business_id;
        $variationLocation->branch_id    = $branch_id;
        $variationLocation->product_id   = $product->id;
        $variationLocation->variation_id = $request->variation_id;
        $variationLocation->product_variation_id = $productVariation->id;
        $variationLocation->qty_available = $request->quantity == '' ? 0 : $request->quantity;
        $variationLocation->save();
    }

    public function delete($id)
    {
        $product   = $this->getFind($id);
        if ($product) :
            $this->uploadRepo->deleteImage($product->image_id, 'delete');
        endif;
        return $this->model::destroy($id);
    }

    public function duplicateStore($request)
    {
        try {
            if (isUser()) :
                $request['branches'] = [Auth::user()->branch_id];
            endif;

            if (isSuperadmin()) :
                $business_id   = $request->business_id;
            else :
                $business_id   = business_id();
            endif;
            $business               = Business::find($business_id);
            $product                = new $this->model();
            $product->business_id   = $business_id;
            $product->name          = $request->name;
            if (!blank($business->sku_prefix)) :
                $sku = $business->sku_prefix . '-' . $this->skuGenerate();
            else :
                $sku = $this->skuGenerate();
            endif;
            $product->sku           = $sku;
            $product->unit_id       = $request->unit_id;
            $product->brand_id      = $request->brand_id;
            $product->category_id   = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->tax_id        = $request->tax_id;
            $product->warranty_id   = $request->warranty_id;
            $product->barcode_type  = $request->barcode_type;
            if ($request->enable_stock) :
                $product->enable_stock   = $request->enable_stock == 'on' ? Status::ACTIVE : Status::INACTIVE;
                $product->alert_quantity = $request->alert_quantity;
            endif;
            $product->default_quantity   = $request->quantity;
            $product->variation_id   = $request->variation_id;
            $product->image_id       = $this->uploadRepo->uploadImage($request->product_image, 'product/', [ImageSize::PRODUCT_IMAGE_ONE, ImageSize::PRODUCT_IMAGE_TWO, ImageSize::PRODUCT_IMAGE_THREE], '');
            $product->purchase_price = $request->default_purchase_price;
            $product->sell_price     = $request->selling_price;
            $product->description    = $request->description;
            $product->created_by     = Auth::user()->id;
            $product->save();
            foreach ($request->variation_values as $key => $variationValue) {
                $sub_sku = $product->sku . random_int(100, 999);
                $productVariation  = $this->productVariation($request, $product, $variationValue, $sub_sku);
                foreach ($request->branches as $branch_id) {
                    $this->VariationLocation($request, $product, $productVariation, $branch_id, $business_id);
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function barcodePrintProductVariation($request)
    {
        return ProductVariation::whereIn('id', $request->product_variation)->get();
    }

    public function stockAlertProductVariationLocations()
    {
        return VariationLocationDetails::with(['branch', 'product', 'ProductVariation'])->where(function ($query) {
            $query->whereHas('product', function ($query) {
                $query->where('business_id', business_id());
            });
            if (isUser()) :
                $query->where('branch_id', Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->get();
    }

    public function getSearchProducts($request)
    {

        return $this->model::with(['branchs', 'upload', 'brand', 'category', 'subcategory', 'warranty'])->where(function ($query) use ($request) {
            if (business()) :
                $query->where('business_id', business_id());
            elseif (isUser()) :
                $query->where(['business_id' => business_id()]);
                $query->whereHas('VariationLocations', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                });
            endif;
            $query->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('sku', 'LIKE', '%' . $request->search . '%');
                $query->orWhereHas('brand', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
                $query->orWhereHas('category', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
                $query->orWhereHas('subcategory', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
            });
        })->orderByDesc('id')->limit(30)->get();
    }

    public function getProducts($request)
    {

        return VariationLocationDetails::with('product', 'ProductVariation', 'variation')->where(function ($query) use ($request) {
            $query->where('business_id', business_id());
            if (business()) :
                if ($request->branch_id) :
                    $query->where('branch_id', $request->branch_id);
                endif;
            else :
                $query->where('branch_id', Auth::user()->branch_id);
            endif;
            $query->where(function ($query) use ($request) {
                if ($request->category_id || $request->brand_id) :
                    $query->whereHas('product', function ($query) use ($request) {
                        if (!blank($request->category_id)) :
                            $query->where('category_id', $request->category_id);
                        endif;
                        if (!blank($request->brand_id)) :
                            $query->Where('brand_id', $request->brand_id);
                        endif;
                    });
                endif;
            });
        })->orderByDesc('id')->paginate(16);
    }
}
