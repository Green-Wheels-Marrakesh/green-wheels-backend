<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function getNbClients() : JsonResponse {
        $nbClients = Client::count();
        return response()->json([
            'result' => $nbClients,
        ]);
    }

    function getNbBikes() : JsonResponse {
        $nbBikes = Bike::count();
        return response()->json([
            'result' => $nbBikes,
        ]);
    }
    
    function getNbBookings(Request $request) : JsonResponse {
        $nbBookings = $this->nbBookings($request);
        return response()->json([
            'result' => $nbBookings,
        ]);
    }

    function getNbBookingsByYear(Request $request, int $year) : JsonResponse {
        $nbBookings = collect();
        for ($i=1; $i <= 12; $i++) { 
            $nbBookings->put($i, $this->nbBookings($request->merge([
                'month' => $i,
                'year' => $year,
            ])));
        }
        return response()->json([
            'result' => $nbBookings,
        ]);
    }

    function getTotalPriceBookings(Request $request) : JsonResponse {
        $totalPriceBookings = $this->totalPrice($request->merge([
            'type' => 'booking',
        ]));
        return response()->json([
            'result' => $totalPriceBookings,
        ]);
    }

    function getTotalPriceBookingsByYear(Request $request, int $year) : JsonResponse {
        $totalPriceBookings = collect();
        for ($i=1; $i <= 12; $i++) { 
            $totalPriceBookings->put($i, $this->totalPrice($request->merge([
                'month' => $i,
                'year' => $year,
                'type' => 'booking',
            ])));
        }
        return response()->json([
            'result' => $totalPriceBookings,
        ]);
    }
    
    function getTotalPriceSellings(Request $request) : JsonResponse {
        $totalPriceSellings = $this->totalPrice($request->merge([
            'type' => 'selling',
        ]));
        return response()->json([
            'result' => $totalPriceSellings,
        ]);
    }

    function getTotalPriceSellingsByYear(Request $request, int $year) : JsonResponse {
        $totalPriceSellings = collect();
        for ($i=1; $i <= 12; $i++) { 
            $totalPriceSellings->put($i, $this->totalPrice($request->merge([
                'month' => $i,
                'year' => $year,
                'type' => 'selling',
            ])));
        }
        return response()->json([
            'result' => $totalPriceSellings,
        ]);
    }

    function getLastOperations(Request $request) : JsonResponse {
        $lastOperations = Operation::when($request->client, function (Builder $query, int $client) {
            $query->whereRelation('booking', 'client_id', $client)
                ->orWhereRelation('selling', 'client_id', $client);
        })
        ->take($request->nb ?? 5)
        ->with([
            'booking.client.person',
            'selling.client.person',
        ])
        ->get();
        return response()->json([
            'result' => $lastOperations,
        ]);
    }

    function getBookings(Request $request) : JsonResponse {
        $bookings = Booking::when($request->bike, function (Builder $query, int $bike) {
            $query->where('bike_variant_id', $bike);
        })
        ->when($request->month, function (Builder $query, int $month) {
            $query->whereHas('operation', function (Builder $query) use ($month) {
                $query->whereMonth('date_operation', $month);
            });
        })
        ->when($request->year, function (Builder $query, int $year) {
            $query->whereHas('operation', function (Builder $query) use ($year) {
                $query->whereYear('date_operation', $year);
            });
        })
        ->when($request->date, function (Builder $query, string $date) {
            $query->whereHas('operation', function (Builder $query) use ($date) {
                $query->whereDate('date_operation', $date);
            });
        })
        ->when($request->from_date, function (Builder $query, string $fromDate) {
            $query->whereHas('operation', function (Builder $query) use ($fromDate) {
                $query->whereDate('date_operation', '>=', $fromDate);
            });
        })
        ->when($request->to_date, function (Builder $query, string $toDate) {
            $query->whereHas('operation', function (Builder $query) use ($toDate) {
                $query->whereDate('date_operation', '<=', $toDate);
            });
        })
        ->with([
            'operation',
            'client.person',
            'bike_variant.article.reference',
            'bike_variant.bike',
        ])
        ->get()
        ->mapWithKeys(function (Booking $booking, int $key) {
            return [$key => [
                'title' => $booking->bike_variant->article->reference->generated_reference,
                'start' => $booking->operation->date_operation,
                'end' => $booking->date_end,
                'booking_all_details' => $booking,
            ]];
        });
        return response()->json([
            'result' => $bookings,
        ]);
    }

    function getTotalPriceOperations(Request $request) : JsonResponse {
        $totalPriceOperations = $this->totalPrice($request);
        return response()->json([
            'result' => $totalPriceOperations,
        ]);
    }

    function getTotalPriceOperationsByYear(Request $request, int $year) : JsonResponse {
        $totalPriceOperations = collect();
        for ($i=1; $i <= 12; $i++) { 
            $totalPriceOperations->put($i, $this->totalPrice($request->merge([
                'month' => $i,
                'year' => $year,
            ])));
        }
        return response()->json([
            'result' => $totalPriceOperations,
        ]);
    }
    // function getTotalPriceMarginByYear(Request $request, int $year) : JsonResponse {
    //     $totalPriceOperations = collect();
    //     for ($i=1; $i <= 12; $i++) { 
    //         $totalPriceOperations->put($i, $this->totalPrice($request->merge([
    //             'month' => $i,
    //             'year' => $year,
    //             'type' => 'selling',
    //         ]))  /*- here the total of buying ()*/);
    //     }
    //     return response()->json([
    //         'result' => $totalPriceOperations,
    //     ]);
    // }

    private function totalPrice(Request $request) : float {
        $totalPriceBookings = Operation::when($request->bike, function (Builder $query, int $bike) {
            $query->whereRelation('booking', 'bike_variant_id', $bike);
        })
        ->when($request->client, function (Builder $query, int $client) {
            $query->where(function (Builder $query) use ($client) {
                $query->whereRelation('booking', 'client_id', $client)
                ->orWhereRelation('selling', 'client_id', $client);
            });
        })
        ->when($request->month, function (Builder $query, int $month) {
            $query->whereMonth('date_operation', $month);
        })
        ->when($request->year, function (Builder $query, int $year) {
            $query->whereYear('date_operation', $year);
        })
        ->when($request->date, function (Builder $query, string $date) {
            $query->whereDate('date_operation', $date);
        })
        ->when($request->type, function (Builder $query, string $type) {
            if ($type == 'booking') {
                $query->has('booking');
            } elseif ($type == 'selling') {
                $query->has('selling');
            } else {
                $query;
            }
        })
        ->sum('price_operation');
        return $totalPriceBookings;
    }

    private function nbBookings(Request $request) : int {
        $nbBookings = Booking::when($request->bike, function (Builder $query, int $bike) {
            $query->where('bike_variant_id', $bike);
        })
        ->when($request->client, function (Builder $query, int $client) {
            $query->where('client_id', $client);
        })
        ->when($request->month, function (Builder $query, int $month) {
            $query->whereHas('operation', function (Builder $query) use ($month) {
                $query->whereMonth('date_operation', $month);
            });
        })
        ->when($request->year, function (Builder $query, int $year) {
            $query->whereHas('operation', function (Builder $query) use ($year) {
                $query->whereYear('date_operation', $year);
            });
        })
        ->when($request->date, function (Builder $query, string $date) {
            $query->whereHas('operation', function (Builder $query) use ($date) {
                $query->whereDate('date_operation', $date);
            });
        })
        ->count();
        return $nbBookings;
    }
}
