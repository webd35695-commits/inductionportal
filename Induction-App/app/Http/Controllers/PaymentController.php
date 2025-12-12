<?php

namespace App\Http\Controllers;

use App\Models\AppliedPosts;
use App\Models\Payment;
use App\Models\User;
use App\Services\OneLinkService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function show(AppliedPosts $appliedPost)
    {
        if ($appliedPost->user_id !== auth()->id()) {
            abort(403);
        }
       $applicatDetail = User::with('candidateProfile')
    ->find($appliedPost->user_id);

        $appliedPost->load('post', 'latestPayment');

        return Inertia::render('Candidate/Payment', [
            'applicant_name' => $applicatDetail->name,

            'application' => $appliedPost,
            'amount' => (float) ($appliedPost->post->fee_post ?? 0),
            'latestPayment' => $appliedPost->latestPayment,
        ]);
    }

    public function initiate(AppliedPosts $appliedPost, Request $request, OneLinkService $oneLink)
    {
        if ($appliedPost->user_id !== auth()->id()) {
            abort(403);
        }

        $amount = (float) ($appliedPost->post->fee_post ?? 0);
        if ($amount <= 0) {
            return back()->with('error', 'No fee defined for this post.');
        }

        $payment = Payment::create([
            'applied_post_id' => $appliedPost->id,
            'amount' => $amount,
            'status' => 'initiated',
            'provider' => '1LINK',
        ]);

        $data = $oneLink->initiate($payment);
        $payment->update(['reference_no' => $data['reference']]);

        return redirect()->away($data['redirect_url']);
    }

    public function callback(Request $request, OneLinkService $oneLink)
    {
        $verified = $oneLink->verify($request->all());
        $payment = Payment::where('reference_no', $verified['reference_no'])->first();

        if (!$payment) {
            abort(404);
        }

        $status = $verified['status'] === 'paid' ? 'paid' : 'failed';
        $payment->update([
            'status' => $status,
            'transaction_id' => $verified['transaction_id'] ?? null,
            'meta' => $verified['meta'] ?? null,
            'paid_at' => $status === 'paid' ? now() : null,
        ]);

        return redirect()->route('candidate.applications.payment', $payment->applied_post_id)
            ->with($status === 'paid' ? 'success' : 'error', $status === 'paid' ? 'Payment successful.' : 'Payment failed.');
    }

    // Show/Generate challan for applicant
    public function challan(AppliedPosts $appliedPost, OneLinkService $oneLink)
    {
        if ($appliedPost->user_id !== auth()->id()) {
            abort(403);
        }
        $appliedPost->load('post','user.candidateProfile');
        $amount = (float) ($appliedPost->post->fee_post ?? 0);
        $payment = $appliedPost->latestPayment;
        if (!$payment) {
            $payment = Payment::create([
                'applied_post_id' => $appliedPost->id,
                'amount' => $amount,
                'status' => 'pending',
                'provider' => '1LINK',
            ]);
        }
        if (!$payment->reference_no) {
            $data = $oneLink->initiate($payment);
            $payment->update(['reference_no' => $data['reference']]);
        }
        return Inertia::render('Candidate/Challan', [
            'application' => $appliedPost,
            'payment' => $payment->fresh(),
        ]);
    }

    // Mock redirect route to simulate provider screen
    public function mockRedirect(Request $request)
    {
        $ref = $request->query('ref');
        $pid = $request->query('pid');
        // Immediately redirect back as if provider returned success
        return redirect()->route('payments.callback', ['ref' => $ref, 'status' => 'paid', 'txn_id' => 'SIM-' . $pid]);
    }
}
