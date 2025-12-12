<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Candidate\CandidateProfile;
use App\Models\AgeRelaxation;
use App\Models\AppliedPosts;
use App\Models\Payment;
use Spatie\Permission\Models\Role;

class DummyApplicantsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_PK');

        // =============================================================
        // 1. ENSURE EXACTLY 1000 CANDIDATES (No duplicates, safe to re-run)
        // =============================================================
        $existing = User::role('candidate')->count();

        if ($existing >= 1000) {
            $this->command->info("Already have {$existing} candidates. Skipping user creation.");
        } else {
            $this->command->info("Creating " . (1000 - $existing) . " new realistic Pakistani candidates...");
        }

        // Real Pakistani names
        $maleFirst   = ['Ahmed','Ali','Hassan','Hussain','Abdullah','Bilal','Usman','Omar','Hamza','Zain','Saad','Fahad','Ahsan','Tahir','Waseem','Kashif','Asif','Arslan','Faisal','Imran','Zeeshan','Adnan','Shahid','Nadeem','Talha','Ibrahim','Yousaf','Farhan','Waqas','Rizwan'];
        $femaleFirst = ['Ayesha','Fatima','Maryam','Hafsa','Zainab','Sana','Saba','Amna','Iqra','Hira','Fiza','Noor','Sidra','Mahnoor','Laiba','Areeba','Hadia','Rabia','Sumaira','Sadia','Kainat','Nimra','Aiman','Farah','Anam','Saima','Bushra','Nida','Tooba','Eman'];
        $lastNames   = ['Khan','Ahmed','Ali','Hussain','Akhtar','Malik','Sheikh','Butt','Chaudhry','Raza','Abbasi','Rajput','Mirza','Qureshi','Siddiqui','Shah','Gujjar','Arain','Jatt','Bhatti','Chohan','Awan','Mughal','Sahi','Ranjha','Gondal','Tarar','Cheema','Warraich','Joyia'];

        $cities = \App\Models\Cities::pluck('id')->toArray();
        $posts  = \App\Models\Post::pluck('id')->toArray();

        if (empty($cities) || empty($posts)) {
            $this->command->error("Cities or Posts missing! Run main seeders first.");
            return;
        }

        Role::firstOrCreate(['name' => 'candidate', 'guard_name' => 'web']);

        // Create missing candidates
        for ($i = $existing + 1; $i <= 1000; $i++) {
            $gender = $faker->randomElement(['Male', 'Female']);
            $first  = $gender === 'Male' ? $faker->randomElement($maleFirst) : $faker->randomElement($femaleFirst);
            $last   = $faker->randomElement($lastNames);
            $name   = ($gender === 'Male' && $faker->boolean(80)) ? "Muhammad $first $last" : "$first $last";

            $dob = $faker->dateTimeBetween('-45 years', '-22 years')->format('Y-m-d');

            $user = User::create([
                'name'              => $name,
                'email'             => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $user->assignRole('candidate');

            // CNIC: Realistic 13-digit
            $cnic = '3' . $faker->numberBetween(1,5) . $faker->numberBetween(10,99) . $faker->numerify('#########');

            // Candidate Profile
            CandidateProfile::create([
                'user_id'       => $user->id,
                'name'          => $name,
                'cnic'          => $cnic,
                'father_name'   => $gender === 'Male'
                    ? $faker->randomElement($maleFirst) . ' ' . $faker->randomElement($lastNames)
                    : 'Mr. ' . $faker->randomElement($lastNames),
                'date_of_birth' => $dob,
                'city_id'      => $faker->randomElement($cities),
                'gender'        => $gender === 'Male' ? 'Male' : 'Female',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            if ($i % 100 == 0) {
                $this->command->info("Created {$i} candidates...");
            }
        }

        // =============================================================
        // 2. NOW TAKE EXACTLY 1000 CANDIDATES FOR FURTHER SEEDING
        // =============================================================
        $candidates = User::role('candidate')->take(1000)->get();
        $this->command->info("Processing data for {$candidates->count()} candidates...");

        // =============================================================
        // 3. SEED REALISTIC QUALIFICATIONS (2–4 per candidate)
        // =============================================================
        $this->seedQualifications($candidates, $faker);

        // =============================================================
        // 4. SEED CONTACT DETAILS
        // =============================================================
        $this->seedContacts($candidates, $faker);

        // =============================================================
        // 5. APPLY TO POSTS + PAYMENTS
        // =============================================================
        $this->seedApplicationsAndPayments($candidates, $faker, $cities, $posts);

        $this->command->info("1000 REAL PAKISTANI CANDIDATES CREATED SUCCESSFULLY!");
        $this->command->info("Login with any candidate email → password: password123");
    }

    private function seedQualifications($candidates, $faker)
    {
        $this->command->info("Seeding qualifications...");

        $types = ['Matriculation','Intermediate','Bachelor','Master','MPhil/PhD'];
        foreach ($types as $type) {
            DB::table('qualification_types')->updateOrInsert(
                ['name' => $type],
                ['name' => $type, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()]
            );
        }
        $typeIds = DB::table('qualification_types')->pluck('id', 'name');

        $institutions = ['University of the Punjab','NUST','LUMS','COMSATS University','FAST-NUCES','UET Lahore','Quaid-e-Azam University','GC University Lahore','Virtual University of Pakistan','Allama Iqbal Open University','IBA Karachi','PIEAS','Air University','Bahria University'];

        foreach ($candidates as $candidate) {
            $num = $faker->numberBetween(2, 4);
            $previousLevel = null;

            for ($j = 0; $j < $num; $j++) {
                $level = match (true) {
                    $j === 0 => 'Matriculation',
                    $j === 1 || $previousLevel === 'Matriculation' => 'Intermediate',
                    $j === 2 || $previousLevel === 'Intermediate' => $faker->randomElement(['Bachelor','Bachelor','Bachelor','Master']),
                    default => $faker->randomElement(['Master','MPhil/PhD']),
                };
                $previousLevel = $level;

                $degree = match ($level) {
                    'Matriculation' => 'SSC (Matric) - Science',
                    'Intermediate'  => $faker->randomElement(['FSc Pre-Engineering','FSc Pre-Medical','ICS','I.Com','FA']),
                    'Bachelor'      => $faker->randomElement(['BS Computer Science','BBA','BS Electrical Engineering','BS Software Engineering','B.Com','BS Accounting & Finance']),
                    'Master'        => $faker->randomElement(['MS Computer Science','MBA','MCS','MSc Mathematics','MA English']),
                    'MPhil/PhD'     => $faker->randomElement(['MPhil Computer Science','PhD Physics','MPhil Education']),
                    default => 'BS IT'
                };

                $year = match ($level) {
                    'Matriculation' => $faker->numberBetween(2008, 2015),
                    'Intermediate'  => $faker->numberBetween(2016, 2018),
                    'Bachelor'      => $faker->numberBetween(2019, 2023),
                    default         => $faker->numberBetween(2023, 2025),
                };

                DB::table('qualifications')->updateOrInsert(
                    [
                        'user_id'        => $candidate->id,
                        'degree_type_id' => $typeIds[$level],
                        'degree_name'    => $degree,
                    ],
                    [
                        'institute_name' => $faker->randomElement($institutions),
                        'passing_year'   => $year,
                        'status'         => 'Active',
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]
                );
            }
        }
        $this->command->info("Qualifications seeded!");
    }

    private function seedContacts($candidates, $faker)
    {
        $this->command->info("Seeding contact details...");

        foreach ($candidates as $user) {
            $address = $faker->streetAddress . ", " . $faker->city . ", Pakistan";
            $same = $faker->boolean(85);

            DB::table('user_contacts')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'phone'             => $faker->boolean(65) ? '05' . $faker->randomDigitNotNull . $faker->numerify('#######') : null,
                    'mobile'            => '03' . $faker->randomElement(['0','1','2','3','4']) . $faker->numerify('#######'),
                    'postal_address'    => $address,
                    'permanent_address' => $same ? $address : $faker->streetAddress . ", " . $faker->city . ", Pakistan",
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]
            );
        }
        $this->command->info("Contact details seeded!");
    }

    private function seedApplicationsAndPayments($candidates, $faker, $cities, $posts)
    {
        $this->command->info("Creating applications & payments...");

        foreach ($candidates as $candidate) {
            $numApps = $faker->numberBetween(1, min(4, count($posts)));
            $selected = $faker->randomElements($posts, $numApps);

            foreach ($selected as $post_id) {
                $applied = AppliedPosts::create([
                    'user_id'           => $candidate->id,
                    'post_id'           => $post_id,
                    'preferred_city_id' => $faker->randomElement($cities),
                    'alternative_city_id' => $faker->boolean(70) ? $faker->randomElement($cities) : null,
                    'status'            => $faker->randomElement(['pending','pending','pending','approved']),
                    'applied_at'        => $faker->dateTimeBetween('-90 days', '-2 days'),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                if ($faker->boolean(94)) {
                    Payment::create([
                        'applied_post_id' => $applied->id,
                        'amount'          => 800,
                        'status'          => 'paid',
                        'provider'        => $faker->randomElement(['JazzCash','Easypaisa','Bank Alfalah','HBL','UBL']),
                        'reference_no'    => strtoupper(Str::random(12)),
                        'transaction_id'  => 'TXN' . str_pad(rand(1,9999999), 8, '0', STR_PAD_LEFT),
                        'paid_at'         => $faker->dateTimeBetween('-80 days', '-1 day'),
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }
        }
        $this->command->info("Applications & payments seeded!");
    }
}
