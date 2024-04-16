<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellingRequest;
use App\Models\Client;
use App\Models\Operation;
use App\Models\Selling;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SellingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $sellings = Selling::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query;
                        }),
                    ]);
            })
            ->with([
                'operation',
                'client.person',
                'selling_details.article.reference',
            ])
            ->get();
            return response()->json([
                'result' => $sellings,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SellingRequest $request)
    {
        try {
            $operation = Operation::find($request->operation);
            $client = Client::find($request->client);
            $selling = new Selling($request->all());
            if (
                $selling->operation()->associate($operation) &&
                $selling->client()->associate($client) &&
                $selling->save()
            ) {
                $result = $selling;
                $msg = Str::ucfirst(__('selling was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('selling was not successfully added'));
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
    public function show(Selling $selling)
    {
        try {
            return response()->json([
                'result' => $selling,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SellingRequest $request, Selling $selling)
    {
        try {
            $client = Client::find($request->client);
            if (
                $selling->client()->associate($client) &&
                $selling->update($request->all())
            ) {
                $result = $selling;
                $msg = Str::ucfirst(__('selling was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('selling was not successfully updated'));
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
    public function destroy(Selling $selling)
    {
        try {
            if ($selling->delete()) {
                $result = $selling;
                $msg = Str::ucfirst(__('selling was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('selling was not successfully deleted'));
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
