<?php

namespace App\Http\Controllers;

use App\Http\Requests\TourBookingRequest;
use App\Models\Booking;
use App\Models\TourBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TourBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $tourBookings = TourBooking::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->whereRelation('booking', 'date_end', 'like', "%$value%")
                                ->orWhereRelation('booking', 'child', 'like', "%$value%")
                                ->orWhereRelation('booking', 'guaranty_price', 'like', "%$value%")
                                ->orWhere('tour_mode', 'like', "%$value%")
                                ->orWhere('tour_type', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'booking.operation',
                'booking.client.person',
                'booking.booking_additionals.product_variant.article.reference',
            ])
            ->get();
            return response()->json([
                'result' => $tourBookings,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TourBookingRequest $request)
    {
        try {
            $booking = Booking::find($request->booking);
            $tourBooking = new TourBooking($request->all());
            if (
                $tourBooking->booking()->associate($booking) &&
                $tourBooking->save()
            ) {
                $result = $tourBooking;
                $msg = Str::ucfirst(__('tour booking was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('tour booking was not successfully added'));
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
    public function show(TourBooking $tourBooking)
    {
        try {
            return response()->json([
                'result' => $tourBooking,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TourBookingRequest $request, TourBooking $tourBooking)
    {
        try {
            if ($tourBooking->update($request->all())) {
                $result = $tourBooking;
                $msg = Str::ucfirst(__('tour booking was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('tour booking was not successfully updated'));
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
    public function destroy(TourBooking $tourBooking)
    {
        try {
            if ($tourBooking->delete()) {
                $result = $tourBooking;
                $msg = Str::ucfirst(__('tour booking was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('tour booking was not successfully deleted'));
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
