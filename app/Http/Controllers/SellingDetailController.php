<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellingDetailRequest;
use App\Models\Article;
use App\Models\Selling;
use App\Models\SellingDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SellingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $sellingDetails = SellingDetail::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('selling_price', 'like', "%$value%")
                                ->orWhereRelation('selling.operation', 'date_operation', 'like', "%$value%")
                                ->orWhereRelation('selling.operation', 'advance_price_operation', 'like', "%$value%")
                                ->orWhereRelation('article', 'default_selling_price', 'like', "%$value%")
                                ->orWhereRelation('article', 'qty_notification_setting', 'like', "%$value%")
                                ->orWhereRelation('article.reference', 'generated_reference', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'selling.operation',
                'article.reference',
            ])
            ->get();
            return response()->json([
                'result' => $sellingDetails,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SellingDetailRequest $request)
    {
        try {
            $article = Article::find($request->article);
            $selling = Selling::find($request->selling);
            $sellingDetail = new SellingDetail($request->all());
            if (
                $sellingDetail->article()->associate($article) &&
                $sellingDetail->selling()->associate($selling) &&
                $sellingDetail->save()
            ) {
                $result = $sellingDetail;
                $msg = Str::ucfirst(__('selling detail was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('selling detail was not successfully added'));
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
    public function show(SellingDetail $sellingDetail)
    {
        try {
            return response()->json([
                'result' => $sellingDetail,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SellingDetailRequest $request, SellingDetail $sellingDetail)
    {
        try {
            $article = Article::find($request->article);
            if (
                $sellingDetail->article()->associate($article) &&
                $sellingDetail->update($request->all())
            ) {
                $result = $sellingDetail->refresh();
                $msg = Str::ucfirst(__('selling detail was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('selling detail was not successfully updated'));
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
    public function destroy(SellingDetail $sellingDetail)
    {
        try {
            if ($sellingDetail->delete()) {
                $result = $sellingDetail;
                $msg = Str::ucfirst(__('selling detail was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('selling detail was not successfully deleted'));
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
