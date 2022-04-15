<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $guest = Guest::where('user_id', auth()->user()->id)->get()[0];

        return view('/pages/checkout', compact('rooms', 'guest'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in' => 'required',
            'check_out' => 'required',
            'total_rooms' => 'required',
            'guest_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'room_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data Reservasi kurang lengkap!');
        }

        $room = Room::find($request->room_type);

        $first_day = new DateTime($request->check_in);
        $last_day = new DateTime($request->check_out);
        $interval = $first_day->diff($last_day);
        $total_days = $interval->format('%a');

        $total_cost = $room->cost * $request->total_rooms * $total_days;

        $reservation = Reservation::create([
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_rooms' => $request->total_rooms,
            'guest_name' => $request->guest_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'proccess',
            'total_cost' => $total_cost,
            'room_id' => $request->room_type,
            'guest_id' => auth()->user()->guest->id,
        ]);

        session(['reservation-id' => $reservation->id]);

        return redirect()->route('result')->with('success', 'Reservasi telah berhasil!');
    }
}