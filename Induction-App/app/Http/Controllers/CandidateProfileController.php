<?php

namespace App\Http\Controllers;

use App\Models\Candidate\CandidateProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateProfileRequest;
use App\Http\Requests\UpdateCandidateProfileRequest;
use App\Models\User;
use Inertia\Inertia;
use App\Helpers\CustomHelpers as FetchUserHelper;
use App\Models\AgeRelaxation;
use App\Models\Candidate\qualification;
use App\Models\cities;
use App\Models\District;
use App\Models\Province;
use App\Models\QualificationType;
use App\Models\user_contact;
use App\Models\user_spouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Jobs\UpdateCandidateProfileJob;

class CandidateProfileController extends Controller
{
    public function Profile()
    {
        $userDetails = FetchUserHelper::fetchUserDetails();
        $qualificationData = Qualification::where('user_id', Auth::id())
            ->with('degreeType')
            ->get(['id', 'degree_type_id', 'degree_name', 'institute_name', 'passing_year']);
        //   Log::info('user details that fetch: ' . json_encode($userDetails));
        $ageRelaxation = AgeRelaxation::where('user_id', Auth::id())->first();
        return Inertia::render('Dashboard/Candidate/Profile', [
            'userDetails' => $userDetails,
            'provinces' => Province::all(),
            'qualification_types' => QualificationType::select('id', 'name')->get(),
            'qualificationData' => $qualificationData,
            'ageRelaxation' => $ageRelaxation,
        ]);
    }

    public function fetchDistricts($provinceId)
    {
        $districts = District::where('province_id', $provinceId)->get(['id', 'name']);
        return response()->json(['districts' => $districts]);
    }

    public function getCities($districtId)
    {
        return response()->json([
            'cities' => cities::where('district_id', $districtId)->get()
        ]);
    }

    public function storeQualification(Request $request)
    {

        $request->validate([
            'degree_type_id' => 'required|exists:qualification_types,id',
            'degree_name' => 'required|string|max:255',
            'institute_name' => 'required|string|max:255',
            'passing_year' => 'required|integer|min:1900|max:' . now()->year,
        ]);

        try {
            $qualification = Qualification::create([
                'user_id' => Auth::id(),
                'degree_type_id' => $request->degree_type_id,
                'degree_name' => $request->degree_name,
                'institute_name' => $request->institute_name,
                'passing_year' => $request->passing_year,
            ]);

            return redirect()->back()->with('success', 'Qualifications updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Operation Failed..!');
        }
    }

    public function updateQualification(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:qualifications,id',
            'degree_type_id' => 'required|exists:qualification_types,id',
            'degree_name' => 'required|string|max:255',
            'institute_name' => 'required|string|max:255',
            'passing_year' => 'required|integer|min:1900|max:' . now()->year,
        ]);

        try {
            $qualification = Qualification::where('id', $request->id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$qualification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Qualification not found'
                ], 404);
            }

            $qualification->update([
                'degree_type_id' => $request->degree_type_id,
                'degree_name' => $request->degree_name,
                'institute_name' => $request->institute_name,
                'passing_year' => $request->passing_year,
            ]);

            return redirect()->back()->with('success', 'Qualifications Updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Operation Failed..!');
        }
    }


    public function destroyQualification($id)
    {
        try {
            $qualification = Qualification::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$qualification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Qualification not found'
                ], 404);
            }

            $qualification->delete();

            return redirect()->back()->with('success', 'Qualifications Deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Operation Failed..!!!');
        }
    }

    //    public function update(UpdateCandidateProfileRequest $request, CandidateProfile $candidateProfile)
    //         {

    //             $validated = $request->validated();
    //             UpdateCandidateProfileJob::dispatch(auth()->id(), $validated)
    //                 ->onQueue('profile_updates');
    //                return redirect()->back()->with('message', 'Profile updated successfully');
    //         }
    public function update(UpdateCandidateProfileRequest $request, CandidateProfile $candidateProfile)
    {


        try {
            DB::beginTransaction();
            $photoPath = null;
            if ($request->hasFile('photo')) {
                // Get existing profile to check for old photo
                $existingProfile = CandidateProfile::where('user_id', auth()->id())->first();
                
                if ($existingProfile && $existingProfile->photo_path) {
                    // Delete old photo from storage
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existingProfile->photo_path);
                }

                $photoPath = $request->file('photo')->store('profiles', 'public');
            }

            CandidateProfile::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'name' => $request->name,
                    'father_name' => $request->fatherName,
                    'city_id' => $request->city,
                    'gender' => $request->gender,
                    'marital_status' => $request->maritalStatus,
                    'date_of_birth' => $request->dateOfBirth,
                    'city_id' => $request->city,
                    'disability' => $request->disabilityStatus,
                    'religion' => $request->religion,
                    'cnic' => $request->cnic,
                    'photo_path' => $photoPath ?? CandidateProfile::where('user_id', auth()->id())->value('photo_path'),
                ]
            );
            user_contact::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'phone' => $request->phone,
                    'mobile' => $request->mobile,
                    'permanent_address' => $request->permanentAddress,
                    'postal_address' => $request->postalAddress
                ]
            );
             // Handle spouse information based on marital status
           if ($request->maritalStatus === 'Married' && $request->gender === 'Female' && $request->useHusbandDomicile === 'Yes') {

            // Ensure all required spouse fields are provided
            if (!$request->husbandName || !$request->husbandCnic || !$request->husbandCity) {
                return back()->withErrors([
                    'husbandName' => 'Spouse name is required when using husband\'s domicile.',
                    'husbandCnic' => 'Spouse CNIC is required when using husband\'s domicile.',
                    'husbandCity' => 'Husband\'s city is required when using husband\'s domicile.',
                    'useHusbandDomicile' => 'Please confirm if you are using husband\'s domicile.'
                ]);
            }
            user_spouse::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'spouse_name' => $request->husbandName,
                    'spouse_cnic' => $request->husbandCnic,
                    'city_id' => $request->husbandCity,
                    'useHusbandDomicile' => $request->useHusbandDomicile,

                ]
            );

        } else {
            // Delete spouse record if not married or not using husband's domicile
            user_spouse::where('user_id', Auth::id())->delete();
        }

            DB::commit();
            return redirect()->back()->with('message', 'Profile updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating candidate profile: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update profile. Please try again or contact support.']);
        }
    }
    public function destroy(CandidateProfile $candidateProfile)
    {
        //
    }

    public function updateAgeRelaxation(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'relax_schedule_caste' => 'required|boolean',
            'relax_retired' => 'required|boolean',
            'relax_retired_from' => 'nullable|required_if:relax_retired,true|in:army,navy,air_force',
            'relax_retired_position' => 'nullable|required_if:relax_retired,true|string|max:255',
            'relax_retired_appoint' => 'nullable|required_if:relax_retired,true|date',
            'relax_retired_retired' => 'nullable|required_if:relax_retired,true|date|after:relax_retired_appoint',
            'relax_disable' => 'required|boolean',
            'relax_disabled_nature' => 'nullable|required_if:relax_disable,true|in:Leg/Arm Paralyze,Visual Defect,Mental/Cognitive,Deaf/Dumb,Other',
            'relax_widow' => 'required|boolean',
            'relax_name_employ' => 'nullable|required_if:relax_widow,true|string|max:255',
            'relax_designation' => 'nullable|required_if:relax_widow,true|string|max:255',
            'relax_department' => 'nullable|required_if:relax_widow,true|string|max:255',
            'relax_date_death' => 'nullable|required_if:relax_widow,true|date|after:2005-07-01',
            'gov' => 'required|boolean',
            'gov_name' => 'nullable|required_if:gov,true|string|max:255',
            'gov_designation' => 'nullable|required_if:gov,true|string|max:255',
            'gov_basic_pay' => 'nullable|required_if:gov,true|string|max:255',
            'gov_appoint_date' => 'nullable|required_if:gov,true|date',
            'gov_retire_date' => 'nullable|string|max:255',
            'gov_appoint_nature' => 'nullable|required_if:gov,true|in:contract',
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        try {
            // Update or create the age relaxation record for the authenticated user
            AgeRelaxation::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'relax_schedule_caste' => $request->relax_schedule_caste,
                    'relax_retired' => $request->relax_retired,
                    'relax_retired_from' => $request->relax_retired ? $request->relax_retired_from : null,
                    'relax_retired_position' => $request->relax_retired ? $request->relax_retired_position : null,
                    'relax_retired_appoint' => $request->relax_retired ? $request->relax_retired_appoint : null,
                    'relax_retired_retired' => $request->relax_retired ? $request->relax_retired_retired : null,
                    'relax_disable' => $request->relax_disable,
                    'relax_disabled_nature' => $request->relax_disable ? $request->relax_disabled_nature : null,
                    'relax_widow' => $request->relax_widow,
                    'relax_name_employ' => $request->relax_widow ? $request->relax_name_employ : null,
                    'relax_designation' => $request->relax_widow ? $request->relax_designation : null,
                    'relax_department' => $request->relax_widow ? $request->relax_department : null,
                    'relax_date_death' => $request->relax_widow ? $request->relax_date_death : null,
                    'gov' => $request->gov,
                    'gov_name' => $request->gov ? $request->gov_name : null,
                    'gov_designation' => $request->gov ? $request->gov_designation : null,
                    'gov_basic_pay' => $request->gov ? $request->gov_basic_pay : null,
                    'gov_appoint_date' => $request->gov ? $request->gov_appoint_date : null,
                    'gov_retire_date' => $request->gov ? $request->gov_retire_date : null,
                    'gov_appoint_nature' => $request->gov ? $request->gov_appoint_nature : null,
                ]
            );

            return Redirect::back()->with('message', 'Age relaxation updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating age relaxation: ' . $e->getMessage());
            return Redirect::back()->withErrors(['error' => 'Failed to update age relaxation: ' . $e->getMessage()])->withInput();
        }
    }
}
