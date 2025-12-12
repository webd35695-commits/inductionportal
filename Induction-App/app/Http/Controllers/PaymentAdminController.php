<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentAdminController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['appliedPost.post:id,name','appliedPost.user:id,name'])
            ->latest('id')
            ->simplePaginate(20)
            ->through(function ($p) {
                return [
                    'id' => $p->id,
                    'applied_post_id' => $p->applied_post_id,
                    'amount' => $p->amount,
                    'status' => $p->status,
                    'reference_no' => $p->reference_no,
                    'paid_at' => optional($p->paid_at)->toDateTimeString(),
                    'applied_post' => [
                        'post' => ['name' => optional($p->appliedPost->post)->name],
                        'user' => ['name' => optional($p->appliedPost->user)->name],
                    ],
                ];
            });

        return Inertia::render('Dashboard/Admin/Payments/Index', [
            'payments' => $payments,
        ]);
    }
}
