<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class CandidateQualificationsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_PK');

        // =============================================
        // 1. Ensure exactly 1000 candidates exist
        // =============================================
        $required = 1000;
        $existing = User::role('candidate')->count();

        if ($existing < $required) {
            $this->command->info("Creating " . ($required - $existing) . " new candidate users...");

            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'candidate', 'guard_name' => 'web']);

            for ($i = 0; $i < ($required - $existing); $i++) {
                $user = User::create([
                    'name'              => $faker->name,
                    'email'             => $faker->unique()->safeEmail,
                    'email_verified_at' => now(),
                    'password'          => Hash::make('password'),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $user->assignRole('candidate');
            }
        }

        // Now take exactly 1000 candidates
        $candidates = User::role('candidate')->take(1000)->get();
        $this->command->info("Processing qualifications for {$candidates->count()} candidates...");

        // =============================================
        // 2. Seed Qualification Types
        // =============================================
        $types = [
            'Matriculation', 'Intermediate', 'Bachelor', 'Master', 'MPhil/PhD'
        ];

        foreach ($types as $type) {
            DB::table('qualification_types')->updateOrInsert(
                ['name' => $type],
                ['name' => $type, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $typeIds = [
            'Matriculation' => DB::table('qualification_types')->where('name', 'Matriculation')->value('id'),
            'Intermediate'  => DB::table('qualification_types')->where('name', 'Intermediate')->value('id'),
            'Bachelor'      => DB::table('qualification_types')->where('name', 'Bachelor')->value('id'),
            'Master'        => DB::table('qualification_types')->where('name', 'Master')->value('id'),
            'MPhil/PhD'     => DB::table('qualification_types')->where('name', 'MPhil/PhD')->value('id'),
        ];

        // =============================================
        // 3. Seed Qualification Categories (optional but good)
        // =============================================
        $categories = [
            ['qualification_type_id' => $typeIds['Bachelor'], 'name' => 'Computer Science & IT'],
            ['qualification_type_id' => $typeIds['Bachelor'], 'name' => 'Engineering'],
            ['qualification_type_id' => $typeIds['Bachelor'], 'name' => 'Business Administration'],
            ['qualification_type_id' => $typeIds['Bachelor'], 'name' => 'Commerce & Accounting'],
            ['qualification_type_id' => $typeIds['Master'],  'name' => 'Computer Science & IT'],
            ['qualification_type_id' => $typeIds['Master'],  'name' => 'Business Administration'],
            ['qualification_type_id' => $typeIds['Intermediate'], 'name' => 'Pre-Engineering'],
            ['qualification_type_id' => $typeIds['Intermediate'], 'name' => 'Pre-Medical'],
            ['qualification_type_id' => $typeIds['Intermediate'], 'name' => 'Computer Science'],
        ];

        foreach ($categories as $cat) {
            DB::table('qualification_categories')->updateOrInsert(
                ['qualification_type_id' => $cat['qualification_type_id'], 'name' => $cat['name']],
                $cat + ['status' => 'Active', 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // =============================================
        // 4. Real Pakistani Institutions
        // =============================================
        $institutions = [
            'University of the Punjab', 'NUST', 'LUMS', 'COMSATS University', 'FAST-NUCES',
            'UET Lahore', 'Quaid-e-Azam University', 'GC University Lahore', 'Virtual University of Pakistan',
            'Allama Iqbal Open University', 'IBA Karachi', 'PIEAS', 'Air University', 'Bahria University',
            'Government College University Faisalabad', 'University of Karachi', 'Ned University', 'IQRA University'
        ];

        // =============================================
        // 5. Seed 2–4 Qualifications per Candidate
        // =============================================
        foreach ($candidates as $candidate) {
            $numQualifications = $faker->numberBetween(2, 4);

            for ($i = 0; $i < $numQualifications; $i++) {
                // Progressive education levels (realistic timeline)
                $level = match (true) {
                    $i === 0 || !isset($previousLevel) => 'Matriculation',
                    $i === 1 || $previousLevel === 'Matriculation' => $faker->randomElement(['Intermediate']),
                    $i === 2 || $previousLevel === 'Intermediate' => $faker->randomElement(['Bachelor', 'Bachelor', 'Bachelor', 'Master']), // mostly Bachelor
                    default => $faker->randomElement(['Master', 'MPhil/PhD']),
                };

                $previousLevel = $level;

                $degreeName = match ($level) {
                    'Matriculation' => 'SSC (Matric) - Science',
                    'Intermediate'  => $faker->randomElement([
                        'FSc Pre-Engineering', 'FSc Pre-Medical', 'ICS (Computer Science)', 'I.Com', 'FA General Science'
                    ]),
                    'Bachelor'      => $faker->randomElement([
                        'BS Computer Science', 'BBA', 'BS Electrical Engineering', 'BS Software Engineering',
                        'B.Com', 'BS Accounting & Finance', 'BA LLB', 'BS Mechanical Engineering'
                    ]),
                    'Master'        => $faker->randomElement([
                        'MCS', 'MBA', 'MS Computer Science', 'MSc Mathematics', 'MA English', 'M.Ed'
                    ]),
                    'MPhil/PhD'     => $faker->randomElement(['MPhil Computer Science', 'PhD Physics', 'MPhil Education']),
                    default         => 'BS Information Technology'
                };

                // Realistic passing years (progressive)
                $year = match ($level) {
                    'Matriculation' => $faker->numberBetween(2005, 2015),
                    'Intermediate'  => $faker->numberBetween(2016, 2018),
                    'Bachelor'      => $faker->numberBetween(2019, 2023),
                    'Master', 'MPhil/PhD' => $faker->numberBetween(2022, 2025),
                    default         => 2022
                };

                DB::table('qualifications')->insert([
                    'user_id'         => $candidate->id,
                    'degree_type_id'  => $typeIds[$level],
                    'degree_name'     => $degreeName,
                    'institute_name'  => $faker->randomElement($institutions),
                    'passing_year'    => $year,
                    'status'          => 'Active',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }

        $this->command->info("1000 candidates now have 2–4 realistic qualifications each!");
        $this->command->info("Total qualifications seeded: " . DB::table('qualifications')->whereIn('user_id', $candidates->pluck('id'))->count());
    }
}
