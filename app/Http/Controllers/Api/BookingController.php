<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // ==========================================
    // CREATE BOOKING
    // ==========================================
    public function store(Request $request)
    {
        try {

            // VALIDASI
            $request->validate([

                'name' => 'required',
                'date' => 'required',
                'time' => 'required',
                'room_type' => 'required',
                'table_qty' => 'required',
                'playing_hour' => 'required',
                'total_price' => 'required'

            ]);

            // SIMPAN DATA
            $booking = Booking::create([

                'name' => $request->name,
                'date' => $request->date,
                'time' => $request->time,
                'room_type' => $request->room_type,
                'table_qty' => $request->table_qty,
                'playing_hour' => $request->playing_hour,
                'total_price' => $request->total_price,
                'status' => 'pending'

            ]);

            return response()->json([

                'success' => true,
                'message' => 'Reservation success',
                'data' => $booking

            ], 201);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,
                'message' => $e->getMessage()

            ], 500);
        }
    }

    // ==========================================
    // GET ALL BOOKINGS
    // ==========================================
    public function index()
    {
        try {

            $bookings = Booking::all();

            return response()->json([

                'success' => true,
                'data' => $bookings

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,
                'message' => $e->getMessage()

            ], 500);
        }
    }

    // ==========================================
    // PAYMENT
    // ==========================================
    public function pay($id)
    {
        try {

            $booking = Booking::find($id);

            if (!$booking) {

                return response()->json([

                    'success' => false,
                    'message' => 'Booking not found'

                ], 404);
            }

            $booking->status = 'paid';
            $booking->save();

            return response()->json([

                'success' => true,
                'message' => 'Payment success',
                'data' => $booking

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,
                'message' => $e->getMessage()

            ], 500);
        }
    }

    // ==========================================
    // UPLOAD RECEIPT
    // ==========================================
    public function uploadReceipt(Request $request, $id)
    {
        try {

            $booking = Booking::find($id);

            if (!$booking) {

                return response()->json([

                    'success' => false,
                    'message' => 'Booking not found'

                ], 404);
            }

            if ($request->hasFile('image')) {

                $path = $request->file('image')
                    ->store('receipts', 'public');

                $booking->receipt_image = $path;
                $booking->status = 'waiting';
                $booking->save();

                return response()->json([

                    'success' => true,
                    'message' => 'Receipt uploaded',
                    'image' => $path

                ]);
            }

            return response()->json([

                'success' => false,
                'message' => 'No image uploaded'

            ], 400);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,
                'message' => $e->getMessage()

            ], 500);
        }
    }

    // ==========================================
    // GET TICKET
    // ==========================================
    public function getTicket($id)
    {
        try {

            $booking = Booking::find($id);

            if (!$booking) {

                return response()->json([

                    'success' => false,
                    'message' => 'Booking not found'

                ], 404);
            }

            return response()->json([

                'success' => true,

                'ticket' => [

                    'name' => $booking->name,
                    'date' => $booking->date,
                    'time' => $booking->time,
                    'room_type' => $booking->room_type,
                    'table_qty' => $booking->table_qty,
                    'playing_hour' => $booking->playing_hour,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status

                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,
                'message' => $e->getMessage()

            ], 500);
        }
    }
}