<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = Product::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->Where('product_type', 'like', "%$value%")
                                ->orWhere('product_model', 'like', "%$value%")
                                ->orWhere('product_mark', 'like', "%$value%")
                                ->orWhere('product_status', 'like', "%$value%")
                                ->orWhereRelation('product_variants', 'product_size', 'like', "%$value%")
                                ->orWhereRelation('product_variants.article', 'default_selling_price', 'like', "%$value%")
                                ->orWhereRelation('product_variants.article', 'qty_notification_setting', 'like', "%$value%");
                        }),
                        AllowedFilter::exact('product_type')
                    ]);
            })
            ->with([
                'product_variants.article.reference',
            ])
            ->get();
            return response()->json([
                'result' => $products,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = new Product($request->all());
            if ($product->save()) {
                $result = $product;
                $msg = Str::ucfirst(__('product was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('product was not successfully added'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            return response()->json([
                'result' => $product,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            if ($product->update($request->all())) {
                $result = $product->refresh();
                $msg = Str::ucfirst(__('product was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('product was not successfully updated'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->delete()) {
                $result = $product;
                $msg = Str::ucfirst(__('product was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('product was not successfully deleted'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
