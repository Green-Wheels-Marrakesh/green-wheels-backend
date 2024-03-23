<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $clients = Client::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->whereRelation('person', 'first_name', 'like', "%$value%")
                                ->orWhereRelation('person', 'last_name', 'like', "%$value%")
                                ->orWhereRelation('person', 'cin', 'like', "%$value%")
                                ->orWhereRelation('person', 'passport', 'like', "%$value%")
                                ->orWhereRelation('person', 'phone', 'like', "%$value%")
                                ->orWhereRelation('person', 'contact_email', 'like', "%$value%")
                                ->orWhereRelation('person', 'city', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'person',
            ])
            ->get();
            return response()->json([
                'result' => $clients,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        try {
            $person = Person::find($request->person);
            $client = new Client($request->except([
                'person',
            ]));
            if ($client->person()->associate($person) && $client->save()) {
                $result = $client;
                $msg = Str::ucfirst(__('client was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('client was not successfully added'));
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
    public function show(Client $client)
    {
        try {
            return response()->json([
                'result' => $client,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        try {
            if ($client->update($request->except([
                'person',
            ]))) {
                $result = $client->refresh()->load([
                    'person',
                ]);
                $msg = Str::ucfirst(__('client was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('client was not successfully updated'));
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
    public function destroy(Client $client)
    {
        try {
            if ($client->delete()) {
                $result = $client;
                $msg = Str::ucfirst(__('client was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('client was not successfully deleted'));
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
