<?php

namespace App\Services;

use App\Models\Candidate\CandidateProfile as CandidateCandidateProfile;
use Carbon\Carbon;

class AgeRelaxationService
{
    private const RELAXATION_RULES = [
        'schedule_caste' => 3,
        'disability' => 10,
        'widow' => 5,
        'government' => 10,
        'armed_forces_15_plus' => 15,
    ];

    /**
     * Calculate total age relaxation in days
     */
    public static function calculateRelaxationDays(CandidateCandidateProfile $profile, ?object $ageRelaxation = null): int
    {
        if (!$ageRelaxation) {
            return 0;
        }
        $totalDays = 0;

        // Schedule Caste - 3 years
        if ($ageRelaxation->relax_schedule_caste) {
            $totalDays += self::RELAXATION_RULES['schedule_caste'] * 365;
        }

        // Disability - 10 years
        if ($ageRelaxation->relax_disable) {
            $totalDays += self::RELAXATION_RULES['disability'] * 365;
        }

        // Widow/Widower - 5 years
        if ($ageRelaxation->relax_widow) {
            $totalDays += self::RELAXATION_RULES['widow'] * 365;
        }

        // Government Employee - 10 years (if service >= 2 years)
        if ($ageRelaxation->gov && self::isValidGovernmentService($ageRelaxation)) {
            $totalDays += self::RELAXATION_RULES['government'] * 365;
        }

        // Armed Forces
        if ($ageRelaxation->relax_retired && self::isValidArmedService($ageRelaxation)) {
            $totalDays += self::calculateArmedForcesRelaxation($ageRelaxation);

        }
        return $totalDays;
    }

    private static function isValidGovernmentService($ageRelaxation): bool
    {
        return $ageRelaxation->gov_appoint_date && 
               $ageRelaxation->gov_retire_date &&
               Carbon::parse($ageRelaxation->gov_retire_date)
                   ->diffInYears(Carbon::parse($ageRelaxation->gov_appoint_date)) >= 2;
    }

    private static function isValidArmedService($ageRelaxation): bool
    {
        return $ageRelaxation->relax_retired_appoint && $ageRelaxation->relax_retired_retired;
    }

    private static function calculateArmedForcesRelaxation($ageRelaxation): int
    {
        $appointDate = Carbon::parse($ageRelaxation->relax_retired_appoint);
        $retireDate = Carbon::parse($ageRelaxation->relax_retired_retired);
        
        $serviceYears = $appointDate->diffInYears($retireDate);
        $totalServiceDays = $appointDate->diffInDays($retireDate);
        return $serviceYears >= 15 
            ? self::RELAXATION_RULES['armed_forces_15_plus'] * 365 
            : $totalServiceDays;
    }
}