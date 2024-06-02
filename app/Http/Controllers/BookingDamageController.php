<?php

namespace App\Http\Controllers;

use App\Http\Requests\bookingDamageRequest;
use App\Models\bookingDamage;
use App\Models\BookingDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookingDamageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $bookingDamages = bookingDamage::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('damage_estimated_price', 'like', "%$value%")
                                ->where('damage_type', 'like', "%$value%")
                                ->where('damage_date', 'like', "%$value%")
                                ->where('damage_description', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking.operation', 'date_operation', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking.operation', 'advance_price_operation', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking', 'date_end', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking', 'responsable', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking', 'pick_up_date', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking', 'pick_up_location', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking', 'booking_payment_status', 'like', "%$value%")
                                ->orWhereRelation('booking_detail.booking', 'notes', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'booking_detail.booking.operation',
                'booking_detail.bike_variant.article.reference',
                'booking_detail.bike_variant.bike',
            ])
            ->get();
            return response()->json([
                'result' => $bookingDamages,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(bookingDamageRequest $request)
    {
        try {
            $bookingDetail = BookingDetail::find($request->booking_detail);
            $bookingDamage = new BookingDamage($request->all());
            if (
                $bookingDamage->booking_detail()->associate($bookingDetail) &&
                $bookingDamage->save()
            ) {
                $result = $bookingDamage;
                $msg = Str::ucfirst(__('booking damage was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking damage was not successfully added'));
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
    public function show(bookingDamage $bookingDamage)
    {
        try {
            return response()->json([
                'result' => $bookingDamage,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(bookingDamageRequest $request, bookingDamage $bookingDamage)
    {
        try {
            if ($bookingDamage->update($request->all())) {
                $result = $bookingDamage->refresh();
                $msg = Str::ucfirst(__('booking damage was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking damage was not successfully updated'));
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
    public function destroy(bookingDamage $bookingDamage)
    {
        try {
            if ($bookingDamage->delete()) {
                $result = $bookingDamage;
                $msg = Str::ucfirst(__('booking damage was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking damage was not successfully deleted'));
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
