<?php

namespace App\Http\Controllers;

use App\Models\QuotaSetting;
use App\Models\Post;
use App\Models\Province;
use App\Models\QualificationType;
use App\Models\InductionPhase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotaSettingRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateQuotaSettingRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use DB;

class QuotaSettingController extends Controller
{
    public function index()
    {


        $QuotaAllocation = QuotaSetting::with(['inductionPhase', 'post', 'province'])->get();
        return Inertia::render('Dashboard/Admin/QuotaSetting/Index', [
            'inductionPhases' => InductionPhase::all(),
            'Province' => Province::all(),
            'QuotaAllocation' => $QuotaAllocation,
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Admin/QuotaSetting/Create', [
            'inductionPhases' => InductionPhase::all(),
            'provinces' => Province::all(),
        ]);
    }

    public function getByInductionPhase(Request $request)
    {
        $posts = Post::where('induction_phase_id', $request->induction_phase_id)
            ->select('id', 'name', 'post_abbreviation')
            ->get();
        return response()->json($posts);
    }
    public function getPostDetail(Request $request)
    {
        Log::info('Post id:' . $request->post_id);
        $posts = Post::where('id', $request->post_id)
            ->select('id', 'name', 'number_post', 'post_abbreviation')
            ->get();
        return response()->json($posts);
    }



    public function store(Request $request)
    {
        $request->validate([
            '*.induction_phase_id' => 'required|exists:induction_phases,id',
            '*.post_id' => 'required|exists:posts,id',
            '*.province_id' => 'nullable|exists:provinces,id',
            '*.open_merit' => 'nullable|integer|min:0',
            '*.merit' => 'nullable|integer|min:0',
            '*.women' => 'nullable|integer|min:0',
            '*.minority' => 'nullable|integer|min:0',
            '*.special_persons' => 'nullable|integer|min:0',
        ]);

        foreach ($request->all() as $quotaData) {
            QuotaSetting::updateOrCreate(
                [
                    'induction_phase_id' => $quotaData['induction_phase_id'],
                    'post_id' => $quotaData['post_id'],
                    'province_id' => $quotaData['province_id'],
                ],
                [
                    'open_merit' => $quotaData['open_merit'] ?? 0,
                    'merit' => $quotaData['merit'] ?? 0,
                    'women' => $quotaData['women'] ?? 0,
                    'minority' => $quotaData['minority'] ?? 0,
                    'special_persons' => $quotaData['special_persons'] ?? 0,
                ]
            );
        }

    return redirect()->back()->with('success', 'Quota settings updated successfully!');
}
    // public function store(StoreQuotaSettingRequest $request)
    // {

    //     $validatedData = $request->validated();

    //     // Create multiple records
    //     foreach ($validatedData as $data) {
    //         QuotaSetting::create($data);
    //     }

    //     return redirect()->route('QuotaSetting.index')
    //         ->with('success', 'Quota settings created successfully.');
    // }

    // ... rest of your controller methods
}
