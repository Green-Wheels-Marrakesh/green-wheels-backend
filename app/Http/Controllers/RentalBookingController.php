<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalBookingRequest;
use App\Models\Booking;
use App\Models\RentalBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RentalBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $rentalBookings = RentalBooking::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->whereRelation('booking', 'date_end', 'like', "%$value%")
                                ->orWhereRelation('booking', 'child', 'like', "%$value%")
                                ->orWhereRelation('booking', 'guaranty_price', 'like', "%$value%");
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
                'result' => $rentalBookings,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RentalBookingRequest $request)
    {
        try {
            $booking = Booking::find($request->booking);
            $rentalBooking = new RentalBooking($request->all());
            if (
                $rentalBooking->booking()->associate($booking) &&
                $rentalBooking->save()
            ) {
                $result = $rentalBooking;
                $msg = Str::ucfirst(__('rental booking was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('rental booking was not successfully added'));
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
    public function show(RentalBooking $rentalBooking)
    {
        try {
            return response()->json([
                'result' => $rentalBooking,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RentalBookingRequest $request, RentalBooking $rentalBooking)
    {
        try {
            if ($rentalBooking->update($request->all())) {
                $result = $rentalBooking;
                $msg = Str::ucfirst(__('rental booking was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('rental booking was not successfully updated'));
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
    public function destroy(RentalBooking $rentalBooking)
    {
        try {
            if ($rentalBooking->delete()) {
                $result = $rentalBooking;
                $msg = Str::ucfirst(__('rental booking was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('rental booking was not successfully deleted'));
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
