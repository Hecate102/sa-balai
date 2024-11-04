@extends('layout.header')
@section('content')
    @include('layout.balai-navbar')
    <style>
        .container1 {
            margin-top: 100px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-body p {
            margin-bottom: 0.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #000 !important;
            /* Ensure the text color is black */
        }

        .nav-tabs .nav-link.active {
            border-color: #007bff;
            font-weight: bold;
            color: #007bff;
        }

        .tab-content {
            background: #fff;
            padding: ;
            border-radius: 4px;

        }

        .card-img-top {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            margin-top: 10px;
        }

        .rating-container {
            display: flex;
            justify-content: center;
            /* Center the rating container */
            margin: 20px 0;
            /* Add some vertical margin if needed */
        }
        .count-badge {
        position: absolute;
        top: -5px; /* Adjust as needed */
        right: -10px; /* Adjust as needed */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px; /* Width of the circle */
        height: 20px; /* Height of the circle */
        background-color: red; /* Circle color */
        color: white; /* Text color */
        border-radius: 50%; /* Make it circular */
        font-size: 12px; /* Font size */
        font-weight: bold; /* Make the text bold */
    }

    </style>

<div class="container container1 mt-10">
    <div class="row">
        <!-- User Information -->
        <div class="col-md-3 d-none d-md-block">
            <div class="card">
                <img src="{{ asset('images/default-avatar.png') }}" class="card-img-top" alt="User Image">
                <div class="card-body text-center">
                    <h5 class="card-title">user</h5>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    Details
                    <button class="btn btn-link float-right"><i class="fa fa-ellipsis-v"></i></button>
                </div>
                <div class="card-body">
                    <p><i class="fa fa-envelope"></i> Email: user@gmail.com</p>
                    <p><i class="fa fa-phone"></i> Contact No:</p>
                    <p><i class="fa fa-map-marker"></i> Address:</p>
                    <p><i class="fa fa-info-circle"></i> Description:</p>
                </div>
            </div>
        </div>

        <!-- Booking Tabs -->
        <div class="col-md-9">
            <ul class="nav nav-tabs justify-content-between" id="bookingTabs">

                <li class="nav-item">
                    <a class="nav-link text-black position-relative" href="#pending" data-toggle="tab">
                        Pending
                        @if ($combinedPendingCount > 0)
                        <span class="count-badge">{{ $combinedPendingCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-black position-relative" href="#accept" data-toggle="tab">
                        Accept
                        @if ($combinedAcceptCount > 0)
                        <span class="count-badge">{{ $combinedAcceptCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-black position-relative" href="#review" data-toggle="tab">
                        Review
                        @if ($combinedReviewCount > 0)
                        <span class="count-badge">{{ $combinedReviewCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-black position-relative" href="#cancel" data-toggle="tab">
                        Cancel
                        @if ($combinedCancelCount > 0)
                        <span class="count-badge">{{ $combinedCancelCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>



            <!-- Booking Content -->
            <div class="tab-content mt-3">
                <div class="tab-pane fade" id="pending">
                    @php
                    $hasPendingBookings = false;
                @endphp



                @foreach ($bookings as $booking)

                    @if ($booking->status == 'Pending')
                        @php
                            $hasPendingBookings = true;
                        @endphp



                        <div class="card-header d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <img src="{{ $booking->resort && $booking->resort->userInfo && $booking->resort->userInfo->profilePath ? asset('storage/images/' . $booking->resort->userInfo->profilePath) : asset('images/default-avatar.png') }}"
                                    class="rounded-circle" alt="Product Image"
                                    style="width: 50px; height: 50px; margin-right: 20px;">
                                <strong style="font-weight: bold; font-size: 1rem;">
                                    {{ $booking->resort ? $booking->resort->name : 'Resort Name' }}
                                </strong>
                                {{-- <button class="btn btn-outline-secondary btn-sm"
                                    style="padding: 2px 6px; font-size: 0.75rem;">View Resort</button> --}}

                            </div>
                            <div>
                                <strong class="text-danger"
                                    style="font-weight: bold;">{{ $booking->status }}</strong>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                @if ($booking->room->images->isNotEmpty())
                                    <img src="{{ asset('storage/images/' . $booking->room->images->first()->path) }}"
                                        alt="Product Image" class="rounded"
                                        style="width: 90px; height: 100px; margin-right: 20px;">
                                @else
                                    <img src="https://via.placeholder.com/80" alt="Product Image" class=""
                                        style="width: 90px; height: 100px; margin-right: 20px; ">
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $booking->room->name }}</h5>
                                    <p class="text-muted mb-0">{{ $booking->room->description }}</p>

                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted">

                                </div>
                                <div class="text-danger" style="font-weight: bold;">
                                    ₱{{ $booking->room->price }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center mb-5">
                            <div>

                            </div>
                            <div>
                                <button class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#confirmCancelModal">Cancel</button>

                                <a href="{{ route('chat', $booking->resort->id) }}"
                                    class="btn btn-outline-secondary">Contact Resort</a>
                            </div>
                        </div>

                    @endif

                    <div class="modal fade" id="confirmCancelModal" tabindex="-1"
                        aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmCancelModalLabel">Confirm Cancellation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('booking.cancel', $booking->id) }}" method="POST">
                                        @csrf
                                        <p>Are you sure you want to cancel this booking?</p>
                                        <!-- Reason Input -->
                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Reason for Cancellation</label>
                                            <textarea class="form-control" id="reason" name="reason" rows="3"
                                                placeholder="Enter your reason for cancellation..."></textarea>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach


    {{-- Event Bookings --}}
    @foreach ($eventBookings as $eventBooking)
    @if ($eventBooking->status == 'Pending')
        @php
            $hasPendingBookings = true;
        @endphp

<div class="card shadow mb-4"> <!-- Card with shadow and bottom margin -->
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <div>
            <img src="{{ $eventBooking->resort && $eventBooking->resort->userInfo && $eventBooking->resort->userInfo->profilePath ? asset('storage/images/' . $eventBooking->resort->userInfo->profilePath) : asset('images/default-avatar.png') }}"
                class="rounded-circle" alt="Profile Image"
                style="width: 50px; height: 50px; margin-right: 20px;">
            <strong style="font-weight: bold; font-size: 1rem;">
                {{ $eventBooking->resort ? $eventBooking->resort->name : 'Resort Name' }}
            </strong>
        </div>
        <div>
            <strong class="text-danger" style="font-weight: bold;">{{ $eventBooking->status }}</strong>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center">
            @if ($eventBooking->event && $eventBooking->event->images->isNotEmpty())
                <img src="{{ asset('storage/images/' . $eventBooking->event->images->first()->path) }}"
                    alt="Event Image" class="rounded"
                    style="width: 90px; height: 100px; margin-right: 20px;">
            @else
                <img src="https://via.placeholder.com/80" alt="Placeholder Image" class=""
                    style="width: 90px; height: 100px; margin-right: 20px;">
            @endif
            <div>
                <h5 class="mb-1">{{ $eventBooking->event->name }}</h5>
                <p class="text-muted mb-0">{{ $eventBooking->event->description }}</p>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="text-muted"></div>
            <div class="text-danger" style="font-weight: bold;">
                ₱{{ $eventBooking->event->price }}
            </div>
        </div>
    </div>

    <div class="card-footer bg-white d-flex justify-content-between align-items-center mb-5">
        <div></div>
        <div>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmCancelModal{{ $eventBooking->id }}">Cancel</button>
            <a href="{{ route('chat', $eventBooking->resort->id) }}" class="btn btn-outline-secondary">Contact Resort</a>
        </div>
    </div>

    {{-- Modal for Event Booking Cancellation --}}
    <div class="modal fade" id="confirmCancelModal{{ $eventBooking->id }}" tabindex="-1" aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancelModalLabel">Confirm Cancellation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('eventBooking.cancel', $eventBooking->id) }}" method="POST">
                        @csrf
                        <p>Are you sure you want to cancel this event booking?</p>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Cancellation</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter your reason for cancellation..."></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    @endif
@endforeach

                @if (!$hasPendingBookings)
                    <p>No booking Yet</p>
                @endif

                </div>
                <div class="tab-pane fade" id="accept">
                    @php
                            $hasPendingBookings = false;
                        @endphp
                        @foreach ($bookings as $booking)
                            @if ($booking->status == 'Accept')
                                @php
                                    $hasPendingBookings = true;
                                @endphp

                                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                    <div>
                                        <img src="{{ $booking->resort && $booking->resort->userInfo && $booking->resort->userInfo->profilePath ? asset('storage/images/' . $booking->resort->userInfo->profilePath) : asset('images/default-avatar.png') }}"
                                            class="rounded-circle" alt="Product Image"
                                            style="width: 50px; height: 50px; margin-right: 20px;">
                                        <strong style="font-weight: bold; font-size: 1rem;">
                                            {{ $booking->resort ? $booking->resort->name : 'Resort Name' }}
                                        </strong>
                                        {{-- <button class="btn btn-outline-secondary btn-sm"
                                            style="padding: 2px 6px; font-size: 0.75rem;">View Resort</button> --}}

                                    </div>
                                    <div>


                                        <strong class="text-success"
                                            style="font-weight: bold;">{{ $booking->status }}</strong>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        @if ($booking->room->images->isNotEmpty())
                                            <img src="{{ asset('storage/images/' . $booking->room->images->first()->path) }}"
                                                alt="Product Image" class="rounded"
                                                style="width: 90px; height: 100px; margin-right: 20px;">
                                        @else
                                            <img src="https://via.placeholder.com/80" alt="Product Image" class=""
                                                style="width: 90px; height: 100px; margin-right: 20px; ">
                                        @endif
                                        <div>
                                            <h5 class="mb-1">{{ $booking->room->name }}</h5>
                                            <p class="text-muted mb-0">{{ $booking->room->description }}</p>

                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <div class="text-muted">

                                        </div>
                                        <div class="text-danger" style="font-weight: bold;">
                                            ₱{{ $booking->room->price }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                    <div>

                                    </div>
                                    <div>
                                        <button class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmCancelModal">Cancel</button>

                                        <a href="{{ route('chat', $booking->resort->id) }}"
                                            class="btn btn-outline-secondary">Contact Resort</a>
                                    </div>
                                </div>
                            @endif

                            <div class="modal fade" id="confirmCancelModal" tabindex="-1"
                                aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmCancelModalLabel">Confirm Cancellation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST">
                                                @csrf
                                                <p>Are you sure you want to cancel this booking?</p>
                                                <!-- Reason Input -->
                                                <div class="mb-3">
                                                    <label for="reason" class="form-label">Reason for
                                                        Cancellation</label>
                                                    <textarea class="form-control" id="reason" name="reason" rows="3"
                                                        placeholder="Enter your reason for cancellation..."></textarea>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if (!$hasPendingBookings)
                            <p>No booking Yet</p>
                        @endif

                </div>
                <div class="tab-pane fade" id="review">
                    @php
                    $hasPendingBookings = false;
                @endphp
                @foreach ($bookings as $booking)
                    @if ($booking->status == 'Check Out')
                        @php
                            $hasPendingBookings = true;
                        @endphp

                        <div class="card-header d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <img src="{{ $booking->resort && $booking->resort->userInfo && $booking->resort->userInfo->profilePath ? asset('storage/images/' . $booking->resort->userInfo->profilePath) : asset('images/default-avatar.png') }}"
                                    class="rounded-circle" alt="Product Image"
                                    style="width: 50px; height: 50px; margin-right: 20px;">
                                <strong style="font-weight: bold; font-size: 1rem;">
                                    {{ $booking->resort ? $booking->resort->name : 'Resort Name' }}
                                </strong>
                                {{-- <button class="btn btn-outline-secondary btn-sm"
                                    style="padding: 2px 6px; font-size: 0.75rem;">View Resort</button> --}}

                            </div>
                            <div>


                                <strong class="text-warning"
                                    style="font-weight: bold;">{{ $booking->status }}</strong>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                @if ($booking->room->images->isNotEmpty())
                                    <img src="{{ asset('storage/images/' . $booking->room->images->first()->path) }}"
                                        alt="Product Image" class="rounded"
                                        style="width: 90px; height: 100px; margin-right: 20px;">
                                @else
                                    <img src="https://via.placeholder.com/80" alt="Product Image" class=""
                                        style="width: 90px; height: 100px; margin-right: 20px; ">
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $booking->room->name }}</h5>
                                    <p class="text-muted mb-0">{{ $booking->room->description }}</p>

                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted">

                                </div>
                                <div class="text-danger" style="font-weight: bold;">
                                    ₱{{ $booking->room->price }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <div>

                            </div>
                            <div>
                                <!-- Review Button -->
                                @if (!$userReviews[$booking->id])
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#reviewModal-{{ $booking->id }}">
                                        Leave a Review
                                    </button>
                                    <a href="{{ route('chat', $booking->resort->id) }}"
                                        class="btn btn-outline-secondary">Contact Seller</a>
                                @else
                                    <span class=" text-success">
                                        Thank you for Feedback
                                    </span>
                                @endif


                            </div>
                        </div>
                    @endif

                    <!-- Review Modal -->
                    <div class="modal fade" id="reviewModal-{{ $booking->id }}" tabindex="-1"
                        aria-labelledby="reviewModalLabel-{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reviewModalLabel-{{ $booking->id }}">Leave a
                                        Review for {{ $booking->room->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('reviews.store', $booking->room->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="room_id" value="{{ $booking->room->id }}" required>
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}" required>
                                        <input type="hidden" name="resort_id" value="{{ $booking->resort->id }}" required>

                                        <div class="mb-3">
                                            <label for="feedback" class="form-label">Feedback</label>
                                            <textarea class="form-control" id="feedback" name="review" rows="3" placeholder="Enter your feedback..."></textarea>
                                        </div>

                                        <div class="rating-container">
                                            <div class="rating">
                                                <input type="radio" id="star5" name="rating" value="5">
                                                <label for="star5" title="5 stars">★</label>

                                                <input type="radio" id="star4" name="rating" value="4">
                                                <label for="star4" title="4 stars">★</label>

                                                <input type="radio" id="star3" name="rating" value="3">
                                                <label for="star3" title="3 stars">★</label>

                                                <input type="radio" id="star2" name="rating" value="2">
                                                <label for="star2" title="2 stars">★</label>

                                                <input type="radio" id="star1" name="rating" value="1">
                                                <label for="star1" title="1 star">★</label>
                                            </div>
                                        </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add the following CSS and JavaScript -->
                    <style>
                        .rating {
                            display: flex;
                            flex-direction: row-reverse; /* Stars go from 5 to 1 */
                            justify-content: flex-start;
                        }

                        .rating input {
                            display: none;
                        }

                        .rating label {
                            font-size: 2em;
                            color: #ccc;
                            cursor: pointer;
                        }

                        .rating input:checked ~ label,
                        .rating label:hover,
                        .rating label:hover ~ label {
                            color: gold;
                        }
                    </style>

                    <script>
                        // JavaScript to handle filling stars correctly in reverse order
                        document.querySelectorAll('.rating input').forEach(star => {
                            star.addEventListener('change', function () {
                                const stars = document.querySelectorAll('.rating label');

                                // Reset all stars to gray
                                stars.forEach((label) => {
                                    label.style.color = '#ccc';
                                });

                                // Fill stars up to the selected one
                                this.closest('.rating').querySelectorAll('input').forEach((input) => {
                                    if (input.value <= this.value) {
                                        input.nextElementSibling.style.color = 'gold';
                                    }
                                });
                            });
                        });
                    </script>
                @endforeach

                @if (!$hasPendingBookings)
                    <p>No booking Yet</p>
                @endif
                </div>
                <div class="tab-pane fade" id="cancel">
                    @php
                            $hasPendingBookings = false;
                        @endphp
                        @foreach ($bookings as $booking)
                            @if ($booking->status == 'Cancel')
                                @php
                                    $hasPendingBookings = true;
                                @endphp

                                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                    <div>
                                        <img src="{{ $booking->resort && $booking->resort->userInfo && $booking->resort->userInfo->profilePath ? asset('storage/images/' . $booking->resort->userInfo->profilePath) : asset('images/default-avatar.png') }}"
                                            class="rounded-circle" alt="Product Image"
                                            style="width: 50px; height: 50px; margin-right: 20px;">
                                        <strong style="font-weight: bold; font-size: 1rem;">
                                            {{ $booking->resort ? $booking->resort->name : 'Resort Name' }}
                                        </strong>
                                        {{-- <button class="btn btn-outline-secondary btn-sm"
                                            style="padding: 2px 6px; font-size: 0.75rem;">View Resort</button> --}}
                                    </div>
                                    <div>


                                        <strong class="text-danger"
                                            style="font-weight: bold;">{{ $booking->status }}</strong>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        @if ($booking->room->images->isNotEmpty())
                                            <img src="{{ asset('storage/images/' . $booking->room->images->first()->path) }}"
                                                alt="Product Image" class="rounded"
                                                style="width: 90px; height: 100px; margin-right: 20px;">
                                        @else
                                            <img src="https://via.placeholder.com/80" alt="Product Image" class=""
                                                style="width: 90px; height: 100px; margin-right: 20px; ">
                                        @endif
                                        <div>
                                            <h5 class="mb-1">{{ $booking->room->name }}</h5>
                                            <p class="text-muted mb-0">{{ $booking->room->description }}</p>

                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <div class="text-muted">

                                        </div>
                                        <div class="text-danger" style="font-weight: bold;">
                                            Reason: {{ $booking->reason }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                    <div>

                                    </div>
                                    <div>

                                        <a href="{{ route('chat', $booking->resort->id) }}"
                                            class="btn btn-outline-secondary"><i
                                                class="fa-brands fa-rocketchat"></i>Contact Resort</a>
                                    </div>
                                </div>
                            @endif

                        @endforeach

                        @if (!$hasPendingBookings)
                            <p>No booking Yet</p>
                        @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the active tab from localStorage
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#bookingTabs a[href="' + activeTab + '"]').tab('show');
        }

        // Store the active tab in localStorage when a tab is clicked
        $('#bookingTabs a').on('shown.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
    });
</script>

@endsection
