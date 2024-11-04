<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function dashboard(){
        $users = User::where('id','!=', auth()->user()->id)->get();

        return view('dashboard', compact('users'));
    }

    public function chat($id)
    {
        $authUserId = auth()->user()->id;

        // Mark all unread messages from the selected user as read
        Message::where('sender_id', $id)
            ->where('receiver_id', $authUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Fetch the users who have existing messages with the authenticated user
        $users = User::where('id', '!=', $authUserId)
            ->whereHas('messages', function ($query) use ($authUserId) {
                $query->where('sender_id', $authUserId)
                      ->orWhere('receiver_id', $authUserId);
            })
            ->withCount(['messages' => function ($query) use ($authUserId) {
                $query->where('receiver_id', $authUserId)->where('is_read', false);
            }])
            ->get();

        // Fetch the chat messages between the authenticated user and the selected user
        $messages = Message::where(function($query) use ($authUserId, $id) {
            $query->where('sender_id', $authUserId)
                  ->where('receiver_id', $id);
        })->orWhere(function($query) use ($authUserId, $id) {
            $query->where('sender_id', $id)
                  ->where('receiver_id', $authUserId);
        })->get();

        return view('chat', compact('id', 'users', 'messages'));
    }
    public function chatlist() {
        $currentUserId = auth()->user()->id;
        $currentUserRole = auth()->user()->role;

        // Query to get users who are resorts and have messages with the current user
        if ($currentUserRole === 'resort') {
            // If the authenticated user is a resort, get other users except the resort user
            $users = User::where('role', '!=', 'resort')
                         ->whereHas('messages', function ($query) use ($currentUserId) {
                             // Check if there's a message involving the current user
                             $query->where('sender_id', $currentUserId)
                                   ->orWhere('receiver_id', $currentUserId);
                         })
                         ->withCount('messages') // Get count of messages
                         ->get();
        } else {
            // If the authenticated user is not a resort, get all resort users
            $users = User::where('role', 'resort')
                         ->whereHas('messages', function ($query) use ($currentUserId) {
                             // Check if there's a message involving the current user
                             $query->where('sender_id', $currentUserId)
                                   ->orWhere('receiver_id', $currentUserId);
                         })
                         ->withCount('messages') // Get count of messages
                         ->get();
        }

        \Log::info('Active chats found:', $users->toArray()); // Log for debugging

        return view('mchatlist', compact('users'));
    }









}
