<?php

namespace App\Http\Controllers;

use App\Events\AdminMessageSent;
use App\Events\CandidateMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendAdminMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'candidate_id' => 'required|exists:users,id',
        ]);

        $admin = Auth::user();

        // Save message to database if needed
        // Message::create([...]);

        broadcast(new AdminMessageSent($request->message, $request->candidate_id, $admin->id))->toOthers();

        return response()->json(['status' => 'Message sent']);
    }

    public function sendCandidateMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'admin_id' => 'required|exists:users,id',
        ]);

        $candidate = Auth::user();

        // Save message to database if needed
        // Message::create([...]);

        broadcast(new CandidateMessageSent($request->message, $candidate->id, $request->admin_id))->toOthers();

        return response()->json(['status' => 'Message sent']);
    }
}
