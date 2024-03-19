<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $persons = Person::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->Where('first_name', 'like', "%$value%")
                                ->orWhere('last_name', 'like', "%$value%")
                                ->orWhere('cin', 'like', "%$value%")
                                ->orWhere('passport', 'like', "%$value%")
                                ->orWhere('phone', 'like', "%$value%")
                                ->orWhere('contact_email', 'like', "%$value%")
                                ->orWhere('city', 'like', "%$value%");
                        }),
                    ]);
            })
            ->get();
            return response()->json([
                'result' => $persons,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonRequest $request)
    {
        try {
            $person = new Person($request->all());
            if ($person->save()) {
                $result = $person;
                $msg = Str::ucfirst(__('person was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('person was not successfully added'));
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
    public function show(Person $person)
    {
        try {
            return response()->json([
                'result' => $person,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonRequest $request, Person $person)
    {
        try {
            if ($person->update($request->all())) {
                $result = $person;
                $msg = Str::ucfirst(__('person was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('person was not successfully updated'));
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
    public function destroy(Person $person)
    {
        try {
            if ($person->delete()) {
                $result = $person;
                $msg = Str::ucfirst(__('person was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('person was not successfully deleted'));
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
