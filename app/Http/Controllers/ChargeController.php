<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargeRequest;
use App\Models\Charge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $charges = Charge::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('charge_price', 'like', "%$value%")
                                ->where('charge_date', 'like', "%$value%")
                                ->where('charge_description', 'like', "%$value%");
                        }),
                    ]);
            })
            ->get();
            return response()->json([
                'result' => $charges,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChargeRequest $request)
    {
        try {
            $charge = new Charge($request->all());
            if ($charge->save()) {
                $result = $charge;
                $msg = Str::ucfirst(__('charge was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('charge was not successfully added'));
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
    public function show(Charge $charge)
    {
        try {
            return response()->json([
                'result' => $charge,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChargeRequest $request, Charge $charge)
    {
        try {
            if ($charge->update($request->all())) {
                $result = $charge->refresh();
                $msg = Str::ucfirst(__('charge was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('charge was not successfully updated'));
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
    public function destroy(Charge $charge)
    {
        try {
            if ($charge->delete()) {
                $result = $charge;
                $msg = Str::ucfirst(__('charge was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('charge was not successfully deleted'));
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
