<?php

namespace App\Http\Controllers\Resort;

use App\Http\Controllers\Controller;
use App\Models\EventBooking;
use App\Models\EventBookingImage;
use App\Models\Events;
use App\Models\TemporyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventBookingController extends Controller
{
    public function index()
    {
        $events = Events::all();
        $bookings = EventBooking::all();
        return view('resort.booking.event.event', compact('bookings', 'events'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resort_id' => 'required',
            'event_id' => 'required|exists:events,id', // Validate that the event_id exists in the events table
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'contact' => 'required|numeric|digits_between:11,16',
            'payment' => 'required|numeric',
        ]);

        $temporaryImages = TemporyImage::all(); // Get all temporary images

        if ($validator->fails()) {
            // Delete temporary images on validation failure
            foreach ($temporaryImages as $temporaryImage) {
                Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
                $temporaryImage->delete();
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Add event_id to the create array
        $eventBooking = EventBooking::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'payment' => $request->payment,
            'resort_id' => $request->resort_id,
            'event_id' => $request->event_id,  // Make sure event_id is included here
        ]);

        foreach ($temporaryImages as $temporaryImage) {
            // Move the image from the temporary folder to the final storage
            Storage::move(
                'images/tmp/' . $temporaryImage->folder . '/' . $temporaryImage->file,
                'images/' . $temporaryImage->folder . '/' . $temporaryImage->file
            );

            // Save the image details to a related image table
            EventBookingImage::updateOrCreate([
                'event_booking_id' => $eventBooking->id, // Link image to the booking
                'image' => $temporaryImage->file,
                'path' => 'images/' . $temporaryImage->folder . '/' . $temporaryImage->file,
            ]);

            // Cleanup: delete the temporary folder and image record
            Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
            $temporaryImage->delete();
        }

        return redirect()->back()->with('success', 'Event booking created successfully!');
    }

    public function bookingShow($id)
    {
        $booking = EventBooking::with('images')->findOrFail($id); // Load images related to the booking
        return view('resort.booking.event.bookingDetails', compact('booking'));
    }
    public function updateStatus(Request $request, $id)
    {
        // Find the booking by ID
        $booking = EventBooking::findOrFail($id);

        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_no' => 'required|string|max:255',
            'payment' => 'required|numeric',
        ]);
        // Update the booking status to 'accepted'
        $booking->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact_no,
            'payment' => $request->payment, // Updated to use the correct input name

        ]);
        $booking->status = 'Accept';
        $booking->save();

        // Redirect back with a success message
        return redirect()->route('booking.event')->with('success', 'Booking has been accepted.');
    }
    public function check_outView(Request $request, $id)
    {

        $booking = EventBooking::with('images')->findOrFail($id); // Load images related to the booking
        return view('resort.booking.event.bookingCheckOut', compact('booking'));
    }
    public function checkout(Request $request, $id)
    {
        // Find the booking by ID
        $booking = EventBooking::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'payment' => 'required|numeric', // Ensure payment is a required numeric value
        ]);

        // Update the booking details
        $booking->update([
            'payment' => $request->payment, // Update payment
        ]);

        // Update the status to 'Check Out'
        $booking->status = 'Check Out';
        $booking->save(); // Call save() with parentheses

        // Redirect back with a success message
        return redirect()->route('booking.event')->with('success', 'Booking has been checked out successfully.');
    }


    //registration
    public function registration($id)
    {

        $booking = EventBooking::with('event')->findOrFail($id);

        return view('resort.booking.event.registrationForm', compact('booking'));
    }

    public function cancelBooking(Request $request, $id)
    {

        $booking = EventBooking::findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $booking->update([
            'reason' => $request->reason,
        ]);

        // Update the status to 'Check Out'
        $booking->status = 'Cancel';
        $booking->save();

        return redirect()->route('booking.event')->with('success', 'Booking has been Canceled successfully.');
    }

    public function register($id)
    {

        $event = Events::with('images')->find($id);

        return view('user.resort.eventBooking', compact('event'));
    }

    public function registerStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id', // Validate that the event_id exists in the events table
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'contact' => 'required|numeric|digits_between:11,16',
            'payment' => 'required|numeric',
        ]);

        $temporaryImages = TemporyImage::all(); // Get all temporary images

        if ($validator->fails()) {
            // Delete temporary images on validation failure
            foreach ($temporaryImages as $temporaryImage) {
                Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
                $temporaryImage->delete();
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Retrieve the event and check if it has a user associated with it
        $event = Events::find($request->event_id);

$resort_id = $event->resort_id;


        // Create the event booking with the retrieved resort_id
        $eventBooking = EventBooking::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'payment' => $request->payment,
            'resort_id' => $resort_id,
        ]);


        foreach ($temporaryImages as $temporaryImage) {
            // Move the image from the temporary folder to the final storage
            Storage::move(
                'images/tmp/' . $temporaryImage->folder . '/' . $temporaryImage->file,
                'images/' . $temporaryImage->folder . '/' . $temporaryImage->file
            );

            // Save the image details to a related image table
            EventBookingImage::updateOrCreate([
                'event_booking_id' => $eventBooking->id, // Link image to the booking
                'image' => $temporaryImage->file,
                'path' => 'images/' . $temporaryImage->folder . '/' . $temporaryImage->file,
            ]);

            // Cleanup: delete the temporary folder and image record
            Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
            $temporaryImage->delete();
        }

        return view('successBooking');
    }


}

