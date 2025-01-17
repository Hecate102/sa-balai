<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Events;
use App\Models\Post;
use App\Models\Review;
use App\Models\Room;
use App\Models\TemporyImage;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index($id = 2)
    {
        // Check if the user is logged in
        if (auth()->check()) {
            $authUserId = auth()->user()->id;

            // Fetch users who have messages with the authenticated user and count unread messages
            $users = User::where('id', '!=', $authUserId)
                ->whereHas('messages', function ($query) use ($authUserId) {
                    $query->where('sender_id', $authUserId)
                          ->orWhere('receiver_id', $authUserId);
                })
                ->with(['userInfo', 'messages' => function ($query) use ($authUserId) {
                    $query->where('receiver_id', $authUserId)->where('is_read', false);
                }])
                ->get();
        } else {
            // If the user is not logged in, set users to an empty collection
            $users = collect(); // Empty collection
        }

        // Fetch the resort user by id
        $user = User::where('id', $id)
            ->where('role', 'resort')
            ->with('userInfo') // Include userInfo for the resort user
            ->firstOrFail();

        // Fetch the rooms for the resort user
        $rooms = Room::with('images')->where('user_id', $user->id)->get();

        // Fetch the events
        $events = Events::with('eventImages')->get();

        // Fetch categories with subcategories and their menus and images
        $categories = Category::with('subcategories.menus.images')->get();

        // Fetch reviews with related user and userInfo
        $reviews = Review::with('user.userInfo')->paginate(10);

        // Return the view with the required data
        return view('index', compact('user', 'rooms', 'users', 'events', 'categories', 'reviews'));
    }

    public function balai($id = 2)
    {
         // Check if the user is logged in
         if (auth()->check()) {
            $authUserId = auth()->user()->id;

            // Fetch users who have messages with the authenticated user and count unread messages
            $users = User::where('id', '!=', $authUserId)
                ->whereHas('messages', function ($query) use ($authUserId) {
                    $query->where('sender_id', $authUserId)
                          ->orWhere('receiver_id', $authUserId);
                })
                ->with(['userInfo', 'messages' => function ($query) use ($authUserId) {
                    $query->where('receiver_id', $authUserId)->where('is_read', false);
                }])
                ->get();
        } else {
            // If the user is not logged in, set users to an empty collection
            $users = collect(); // Empty collection
        }

        // Fetch the resort user by id
        $user = User::where('id', $id)
            ->where('role', 'resort')
            ->with('userInfo') // Include userInfo for the resort user
            ->firstOrFail();

        // Fetch the rooms for the resort user
        $rooms = Room::with('images')->where('user_id', $user->id)->get();

        // Fetch the events
        $events = Events::with('eventImages')->get();

        // Fetch categories with subcategories and their menus and images
        $categories = Category::with('subcategories.menus.images')->get();

        // Fetch reviews with related user and userInfo
        $reviews = Review::with('user.userInfo')->paginate(10);


        // Return the view with the required data
        return view('index', compact('user', 'rooms', 'users','events','categories', 'reviews'));
    }




    public function dashboard()
    {

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    }
    // public function welcome()
    // {
    //     $users = User::where('role', 'resort')->with('userInfo')->get();
    //     return view('balai', compact('users'));
    // }
    public function resort($name) {
        // Fetch the user by name and ensure the user is a resort
        $user = User::where('name', $name)->where('role', 'resort')->with('userinfo')->firstOrFail();

        // Check if the authenticated user is the owner of the profile
        $isOwner = Auth::check() && Auth::id() === $user->id;

        // Fetch reviews for the specified user and calculate the average rating
        $averageRating = Review::where('resort_id', $user->id)->avg('rating');

        // Ensure the average rating does not exceed 5
        $averageRating = min($averageRating, 5);

        // Fetch posts for the specified user with their associated files
        $posts = Post::where('user_id', $user->id)->with('files')->get();

        return view('resort.resort-profile', compact('user', 'isOwner', 'averageRating', 'posts'));
    }


    public function resortRoom($name)
    {
        $user = User::where('name', $name)->where('role', 'resort')->with('userinfo')->first();
        $isOwner = false;
        // Fetch reviews for the resort user and calculate the average rating
        $averageRating = Review::where('resort_id', $user->id)->avg('rating');

        // Ensure the average rating does not exceed 5
        $averageRating = min($averageRating, 5);
        $rooms = Room::with('images')->where('user_id', $user->id)->get();
        return view('resort.room', compact('user', 'isOwner', 'rooms', 'averageRating'));
    }

    public function accomodation(){
        return view('accomodation');
    }

    public function create(){
        return view('auth.register');
    }
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $temporaryImages = TemporyImage::all();
        if ($validator->fails()) {

            foreach ($temporaryImages as $temporaryImage) {
                Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
                $temporaryImage->delete();
            }
            return redirect('/')->withErrors($validator)->withInput();
        }



        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Create the associated UserInfo entry
        $userInfo = UserInfo::create([
            'user_id' => $user->id,
            'contactNo' => $request->contact_number,
            'address' => $request->address,

        ]);

        foreach ($temporaryImages as $temporaryImage) {
            // Copy image from temporary storage to the final location
            Storage::copy(
                'images/tmp/' . $temporaryImage->folder . '/' . $temporaryImage->file,
                'images/' . $temporaryImage->folder . '/' . $temporaryImage->file
            );

            // Create a new image record
            UserInfo::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'profilePhoto' => $temporaryImage->file,
                    'profilePath' => $temporaryImage->folder . '/' . $temporaryImage->file,
                ]
            );

            // Cleanup the temporary directory and delete the temporary image record
            Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
            $temporaryImage->delete();
        }

        // Trigger the Registered event
        event(new Registered($user));

        // Log in the new user
        Auth::login($user);

        // Redirect to the desired route after successful registration
        return redirect()->route('balai');
    }
}
