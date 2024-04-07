<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariantRequest;
use App\Models\Article;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = ProductVariant::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('product_size', 'like', "%$value%")
                                ->orWhereRelation('product', 'product_type', 'like', "%$value%")
                                ->orWhereRelation('product', 'product_model', 'like', "%$value%")
                                ->orWhereRelation('product', 'product_mark', 'like', "%$value%")
                                ->orWhereRelation('product', 'product_status', 'like', "%$value%")
                                ->orWhereRelation('article', 'default_selling_price', 'like', "%$value%")
                                ->orWhereRelation('article', 'qty_notification_setting', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'article.reference',
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
    public function store(ProductVariantRequest $request)
    {
        try {
            $article = Article::find($request->article);
            $product = Product::find($request->product);
            $productVariant = new ProductVariant($request->all());
            if (
                $productVariant->article()->associate($article) &&
                $productVariant->product()->associate($product) &&
                $productVariant->save()
            ) {
                $result = $productVariant;
                $msg = Str::ucfirst(__('product variant was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('product variant was not successfully added'));
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
    public function show(ProductVariant $productVariant)
    {
        try {
            return response()->json([
                'result' => $productVariant,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductVariantRequest $request, ProductVariant $productVariant)
    {
        try {
            if ($productVariant->update($request->all())) {
                $result = $productVariant->refresh();
                $msg = Str::ucfirst(__('product variant was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('product variant was not successfully updated'));
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
    public function destroy(ProductVariant $productVariant)
    {
        try {
            if ($productVariant->delete()) {
                $result = $productVariant;
                $msg = Str::ucfirst(__('product variant was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('product variant was not successfully deleted'));
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
