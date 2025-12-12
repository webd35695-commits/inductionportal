<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Candidate\CandidateProfile;
use App\Models\user_contact;
use App\Models\user_spouse;

use App\Models\UserSpouse;

class UpdateCandidateProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $profileData;

    public function __construct($userId, array $profileData)
    {

        $this->userId = $userId;
        $this->profileData = $profileData;
    }

    public function handle()
    {



        try {
            DB::beginTransaction();


            CandidateProfile::updateOrCreate(
                ['user_id' => $this->userId],
                [
                    'name' => $this->profileData['name'],
                    'father_name' => $this->profileData['fatherName'],
                    'city_id' => $this->profileData['city'],
                    'gender' => $this->profileData['gender'],
                    'date_of_birth' => $this->profileData['dateOfBirth'],
                    'disability' => $this->profileData['disabilityStatus'],
                    'religion' => $this->profileData['religion'],
                    'cnic' => $this->profileData['cnic']
                ]
            );


            user_contact::updateOrCreate(
                ['user_id' => $this->userId],
                [
                    'phone' => $this->profileData['phone'],
                    'mobile' => $this->profileData['mobile'],
                    'permanent_address' => $this->profileData['permanentAddress'],
                    'postal_address' => $this->profileData['postalAddress']
                ]
            );


            if ($this->profileData['maritalStatus'] === 'Married' && !empty($this->profileData['spouse_name'])) {
                user_spouse::updateOrCreate(
                    ['user_id' => $this->userId],
                    ['spouse_name' => $this->profileData['spouse_name']]
                );
            } else {
                user_spouse::where('user_id', $this->userId)->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Candidate profile update failed for user {$this->userId}: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
