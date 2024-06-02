<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingChargeRequest;
use App\Models\Booking;
use App\Models\BookingCharge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookingChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $bookingAdditionals = BookingCharge::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('charge_price', 'like', "%$value%")
                                ->where('charge_date', 'like', "%$value%")
                                ->where('charge_description', 'like', "%$value%")
                                ->orWhereRelation('booking.operation', 'date_operation', 'like', "%$value%")
                                ->orWhereRelation('booking.operation', 'advance_price_operation', 'like', "%$value%")
                                ->orWhereRelation('booking', 'date_end', 'like', "%$value%")
                                ->orWhereRelation('booking', 'responsable', 'like', "%$value%")
                                ->orWhereRelation('booking', 'pick_up_date', 'like', "%$value%")
                                ->orWhereRelation('booking', 'pick_up_location', 'like', "%$value%")
                                ->orWhereRelation('booking', 'booking_payment_status', 'like', "%$value%")
                                ->orWhereRelation('booking', 'notes', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'booking.operation',
            ])
            ->get();
            return response()->json([
                'result' => $bookingAdditionals,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingChargeRequest $request)
    {
        try {
            $booking = Booking::find($request->booking);
            $bookingCharge = new BookingCharge($request->all());
            if (
                $bookingCharge->booking()->associate($booking) &&
                $bookingCharge->save()
            ) {
                $result = $bookingCharge;
                $msg = Str::ucfirst(__('booking charge was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking charge was not successfully added'));
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
    public function show(BookingCharge $bookingCharge)
    {
        try {
            return response()->json([
                'result' => $bookingCharge,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingChargeRequest $request, BookingCharge $bookingCharge)
    {
        try {
            if ($bookingCharge->update($request->all())) {
                $result = $bookingCharge->refresh();
                $msg = Str::ucfirst(__('booking charge was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking charge was not successfully updated'));
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
    public function destroy(BookingCharge $bookingCharge)
    {
        try {
            if ($bookingCharge->delete()) {
                $result = $bookingCharge;
                $msg = Str::ucfirst(__('booking charge was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking charge was not successfully deleted'));
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
