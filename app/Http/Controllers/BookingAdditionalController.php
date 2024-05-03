<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingAdditionalRequest;
use App\Models\Booking;
use App\Models\BookingAdditional;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookingAdditionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $bookingAdditionals = BookingAdditional::when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->where('price', 'like', "%$value%")
                                ->orWhereRelation('booking.operation', 'date_operation', 'like', "%$value%")
                                ->orWhereRelation('booking.operation', 'advance_price_operation', 'like', "%$value%")
                                ->orWhereRelation('product_variant.article', 'default_booking_price', 'like', "%$value%")
                                ->orWhereRelation('product_variant.article', 'qty_notification_setting', 'like', "%$value%")
                                ->orWhereRelation('product_variant.article.reference', 'generated_reference', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'booking.operation',
                'product_variant.article.reference',
                'product_variant.product',
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
    public function store(BookingAdditionalRequest $request)
    {
        try {
            $productVariant = ProductVariant::find($request->product_variant);
            $booking = Booking::find($request->booking);
            $bookingAdditional = new BookingAdditional($request->all());
            if (
                $bookingAdditional->product_variant()->associate($productVariant) &&
                $bookingAdditional->booking()->associate($booking) &&
                $bookingAdditional->save()
            ) {
                $result = $bookingAdditional;
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
    public function show(BookingAdditional $bookingAdditional)
    {
        try {
            return response()->json([
                'result' => $bookingAdditional,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingAdditionalRequest $request, BookingAdditional $bookingAdditional)
    {
        try {
            $productVariant = ProductVariant::find($request->product_variant);
            if (
                $bookingAdditional->product_variant()->associate($productVariant) &&
                $bookingAdditional->update($request->all())
            ) {
                $result = $bookingAdditional->refresh();
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
    public function destroy(BookingAdditional $bookingAdditional)
    {
        try {
            if ($bookingAdditional->delete()) {
                $result = $bookingAdditional;
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
