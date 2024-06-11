<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\BikeVariant;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $bookings = Booking::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->Where('date_end', 'like', "%$value%")
                                ->orWhere('responsable', 'like', "%$value%")
                                ->orWhere('pick_up_date', 'like', "%$value%")
                                ->orWhere('pick_up_location', 'like', "%$value%")
                                ->orWhere('booking_payment_status', 'like', "%$value%")
                                ->orWhere('notes', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'operation',
                'rental_booking',
                'tour_booking',
                'booking_charges',
                'client.person',
                'booking_details.bike_variant.article.reference',
                'booking_details.booking.operation',
                'booking_details.booking_damages',
                'booking_additionals.product_variant.article.reference',
            ])
            ->get();
            return response()->json([
                'result' => $bookings,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingRequest $request)
    {
        try {
            $operation = Operation::find($request->operation);
            $client = Client::find($request->client);
            $booking = new Booking($request->all());
            if (
                $booking->operation()->associate($operation) &&
                $booking->client()->associate($client) &&
                $booking->save()
            ) {
                $result = $booking;
                $msg = Str::ucfirst(__('booking was successfully added'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking was not successfully added'));
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
    public function show(Booking $booking)
    {
        try {
            $booking->load('operation', 'client.person', 'bike_variant');
            return response()->json([
                'result' => $booking->load([
                    'client.person',
                    'operation',
                    'booking_additionals.product_variant.article.reference',
                    'booking_charges',
                    'booking_details.booking_damages',
                    'booking_details.bike_variant.article.reference',
                    'booking_details.bike_variant.bike',
                    ]),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingRequest $request, Booking $booking)
    {
        try {
            $client = Client::find($request->client);
            if (
                $booking->client()->associate($client) &&
                $booking->update($request->all())
            ) {
                $result = $booking->load([
                    'rental_booking',
                    'tour_booking',
                ]);
                $msg = Str::ucfirst(__('booking was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking was not successfully updated'));
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
    public function destroy(Booking $booking)
    {
        try {
            if ($booking->delete()) {
                $result = $booking;
                $msg = Str::ucfirst(__('booking was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('booking was not successfully deleted'));
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
