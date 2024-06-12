<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingDetailRequest;
use App\Models\BikeVariant;
use App\Models\Booking;
use App\Models\BookingDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $bookingDetails = BookingDetail::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('booking_price', 'like', "%$value%")
                                ->orWhere('guaranty_price', 'like', "%$value%")
                                ->orWhereRelation('booking.operation', 'date_operation', 'like', "%$value%")
                                ->orWhereRelation('booking.operation', 'advance_price_operation', 'like', "%$value%")
                                ->orWhereRelation('bike_variant.article', 'default_selling_price', 'like', "%$value%")
                                ->orWhereRelation('bike_variant.article', 'default_booking_rental_price', 'like', "%$value%")
                                ->orWhereRelation('bike_variant.article', 'default_booking_tour_price', 'like', "%$value%")
                                ->orWhereRelation('bike_variant.article', 'default_guaranty_price', 'like', "%$value%")
                                ->orWhereRelation('bike_variant.article.reference', 'generated_reference', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'booking.operation',
                'bike_variant.article.reference',
                'bike_variant.bike',
            ])
            ->get();
            return response()->json([
                'result' => $bookingDetails,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingDetailRequest $request)
    {
        try {
            $bikeVariant = BikeVariant::find($request->bike_variant);
            $booking = Booking::find($request->booking);
            $bookingDetail = new BookingDetail($request->all());
            if (
                $bookingDetail->bike_variant()->associate($bikeVariant) &&
                $bookingDetail->booking()->associate($booking) &&
                $bookingDetail->save()
            ) {
                $result = $bookingDetail->load([
                    'booking.operation',
                ]);
                $msg = Str::ucfirst(__('booking detail was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking detail was not successfully added'));
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
    public function show(BookingDetail $bookingDetail)
    {
        try {
            return response()->json([
                'result' => $bookingDetail,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingDetailRequest $request, BookingDetail $bookingDetail)
    {
        try {
            $bikeVariant = BikeVariant::find($request->bike_variant);
            if (
                $bookingDetail->bike_variant()->associate($bikeVariant) &&
                $bookingDetail->update($request->all())
            ) {
                $result = $bookingDetail->refresh()->load([
                    'booking.operation',
                ]);
                $msg = Str::ucfirst(__('booking detail was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking detail was not successfully updated'));
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
    public function destroy(BookingDetail $bookingDetail)
    {
        try {
            if ($bookingDetail->delete()) {
                $result = $bookingDetail->load([
                    'booking.operation',
                ]);
                $msg = Str::ucfirst(__('booking detail was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking detail was not successfully deleted'));
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
