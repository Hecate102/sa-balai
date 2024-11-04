<?php

namespace App\Http\Controllers\resort;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EventBooking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class dashboard extends Controller
{
    public function dashboard(){
        $user = Auth::user();

        // Fetch reviews for the authenticated user and calculate the average rating


        // Ensure the average rating does not exceed 5

        // Get the authenticated user's ID
        $totalClientsWithBookings = Booking::distinct('user_id')->count('user_id');
        $totalPayment = Booking::sum('payment');
        $pendingBookingsCount = Booking::where('status', 'pending')->count();
        $newBookingsCount = Booking::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $eventBooking = EventBooking::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $userId = auth()->id();
        // Retrieve rooms posted by the authenticated user along with their images
        $rooms = Room::with('images')->where('user_id', $userId)->get();


        return view('resort/dashboard', compact('user', 'rooms','totalPayment','pendingBookingsCount'
    ,'newBookingsCount','totalClientsWithBookings', 'eventBooking'));
    }
}
