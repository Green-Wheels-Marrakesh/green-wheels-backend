<?php

namespace App\Http\Controllers;

use App\Http\Requests\BikeVariantRequest;
use App\Models\Article;
use App\Models\Bike;
use App\Models\BikeVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BikeVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $bikes = BikeVariant::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('bike_size', 'like', "%$value%")
                                ->orWhereRelation('bike', 'bike_type', 'like', "%$value%")
                                ->orWhereRelation('bike', 'bike_model', 'like', "%$value%")
                                ->orWhereRelation('bike', 'bike_mark', 'like', "%$value%")
                                ->orWhereRelation('bike', 'bike_status', 'like', "%$value%")
                                ->orWhereRelation('bike', 'qty_notification_setting', 'like', "%$value%")
                                ->orWhereRelation('article', 'default_selling_price', 'like', "%$value%")
                                ->orWhereRelation('article', 'default_booking_tour_price', 'like', "%$value%")
                                ->orWhereRelation('article', 'default_booking_rental_price', 'like', "%$value%")
                                ->orWhereRelation('article', 'default_guaranty_price', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'article.reference',
                'booking_details.booking.operation',
                'booking_details.booking.booking_additionals',
            ])
            ->get();
            return response()->json([
                'result' => $bikes,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BikeVariantRequest $request)
    {
        try {
            $article = Article::find($request->article);
            $bike = Bike::find($request->bike);
            $bikeVariant = new BikeVariant($request->all());
            if (
                $bikeVariant->article()->associate($article) &&
                $bikeVariant->bike()->associate($bike) &&
                $bikeVariant->save()
            ) {
                $result = $bikeVariant;
                $msg = Str::ucfirst(__('bike variant was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('bike variant was not successfully added'));
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
    public function show(BikeVariant $bikeVariant)
    {
        try {
            return response()->json([
                'result' => $bikeVariant,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BikeVariantRequest $request, BikeVariant $bikeVariant)
    {
        try {
            if ($bikeVariant->update($request->all())) {
                $result = $bikeVariant->refresh();
                $msg = Str::ucfirst(__('bike variant was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('bike variant was not successfully updated'));
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
    public function destroy(BikeVariant $bikeVariant)
    {
        try {
            if ($bikeVariant->delete()) {
                $result = $bikeVariant;
                $msg = Str::ucfirst(__('bike variant was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('bike variant was not successfully deleted'));
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
