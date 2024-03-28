<?php

namespace App\Http\Controllers;

use App\Http\Requests\BikeRequest;
use App\Models\Bike;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $bikes = Bike::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->Where('bike_type', 'like', "%$value%")
                                ->orWhere('bike_model', 'like', "%$value%")
                                ->orWhere('bike_mark', 'like', "%$value%")
                                ->orWhere('bike_status', 'like', "%$value%")
                                ->orWhereRelation('bike_variants', 'bike_size', 'like', "%$value%")
                                ->orWhereRelation('bike_variants.article', 'default_selling_price', 'like', "%$value%")
                                ->orWhereRelation('bike_variants.article', 'qty_notification_setting', 'like', "%$value%");
                        }),
                        AllowedFilter::exact('bike_type')
                    ]);
            })
            ->with([
                'bike_variants.article.reference',
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
    public function store(BikeRequest $request)
    {
        try {
            $bike = new Bike($request->all());
            if ($bike->save()) {
                $result = $bike;
                $msg = Str::ucfirst(__('bike was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('bike was not successfully added'));
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
    public function show(Bike $bike)
    {
        try {
            return response()->json([
                'result' => $bike,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BikeRequest $request, Bike $bike)
    {
        try {
            if ($bike->update($request->all())) {
                $result = $bike->refresh();
                $msg = Str::ucfirst(__('bike was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('bike was not successfully updated'));
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
    public function destroy(Bike $bike)
    {
        try {
            if ($bike->delete()) {
                $result = $bike;
                $msg = Str::ucfirst(__('bike was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('bike was not successfully deleted'));
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
