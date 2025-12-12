<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Models\SupportMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupportRequest;
use App\Http\Requests\UpdateSupportRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $tickets = Support::where('sender_id', Auth::id())
        ->withCount([
            'messages as unread_messages_count' => function ($query) {
                $query->where('sender_id', '!=', Auth::id())
                      ->where('is_read', false);
            }
        ])
        ->latest('updated_at')  // Better: show tickets with new replies on top
        ->simplePaginate(15);

    return Inertia::render('Dashboard/Support/Index', [
        'tickets' => $tickets,
    ]);
}

  public function Support_ticket()
{
    return Inertia::render('Dashboard/Support/Support_ticket');
}

    /**
     * Admin: list all tickets
     */
public function adminIndex()
{
    $tickets = Support::with('sender:id,name')
        ->withCount([
            'messages as unread_messages_count' => function ($query) {
                $query->where('sender_id', '!=', Auth::id())
                      ->where('is_read', false);
            }
        ])
        ->latest('updated_at')
        ->simplePaginate(20);

    return Inertia::render('Dashboard/Admin/Support/Index', [
        'tickets' => $tickets,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'department' => 'nullable|string|max:100',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('support/attachments', 'public');
        }

        $ticket = Support::create([
            'sender_id' => Auth::id(),
            'receiver_id' => null,
            'status' => 'Open',
            'ticket_no' => 'TKT-' . Str::upper(Str::random(8)),
            'department' => $data['department'] ?? null,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'attachment' => $path,
        ]);

        return redirect()->route('Support.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Support $support)
{
    if ($support->sender_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin','super_admin','induction'])) {
        abort(403);
    }

    // Mark admin replies as read for the user
    $support->messages()
            ->where('sender_id', '!=', $support->sender_id)
            ->update(['is_read' => true]);

    $support->load(['messages.sender']);
    return Inertia::render('Dashboard/Support/Show', [
        'ticket' => $support,
    ]);
}

// Mark messages as read when admin views ticket
public function adminShow(Support $support)
{
    // Mark user replies as read for admin
    $support->messages()
            ->where('sender_id', $support->sender_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

    $support->load(['messages.sender', 'sender']);
    return Inertia::render('Dashboard/Admin/Support/Show', [
        'ticket' => $support,
    ]);
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Support $support)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        $request->validate([
            'status' => 'required|in:Open,On Hold,Closed',
        ]);
        $support->update(['status' => $request->status]);
        return back()->with('success', 'Ticket status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        if ($support->sender_id !== Auth::id()) abort(403);
        $support->delete();
        return back()->with('success', 'Ticket deleted.');
    }

    public function addMessage(Request $request, Support $support)
    {
        if ($support->sender_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin','super_admin','induction'])) {
            abort(403);
        }
        $data = $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('support/messages', 'public');
        }

        $msg = SupportMessage::create([
            'support_id' => $support->id,
            'sender_id' => Auth::id(),
            'message' => $data['message'],
            'attachment' => $path,
        ]);

        if ($request->wantsJson()) {
            return response()->json($msg->load('sender'));
        }
        return back();
    }

    public function messages(Support $support)
    {
        if ($support->sender_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin','super_admin','induction'])) {
            abort(403);
        }
        $messages = $support->messages()->with('sender:id,name')->latest('id')->take(100)->get()->reverse()->values();
        return response()->json($messages);
    }
}
