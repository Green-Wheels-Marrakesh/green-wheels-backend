<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferenceRequest;
use App\Models\Article;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $references = Reference::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->Where('original_reference', 'like', "%$value%")
                                ->orWhere('generated_reference', 'like', "%$value%");
                        }),
                    ]);
            })
            ->get();
            return response()->json([
                'result' => $references,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReferenceRequest $request)
    {
        try {
            $article = Article::find($request->article);
            $reference = new Reference($request->all());
            if ($reference->article()->associate($article) && $reference->save()) {
                $result = $reference;
                $msg = Str::ucfirst(__('reference was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('reference was not successfully added'));
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
    public function show(Reference $reference)
    {
        try {
            return response()->json([
                'result' => $reference,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReferenceRequest $request, Reference $reference)
    {
        try {
            if ($reference->update($request->all())) {
                $result = $reference;
                $msg = Str::ucfirst(__('reference was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('reference was not successfully updated'));
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
    public function destroy(Reference $reference)
    {
        try {
            if ($reference->delete()) {
                $result = $reference;
                $msg = Str::ucfirst(__('reference was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('reference was not successfully deleted'));
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
