<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class UserContactsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_PK');

        // 1. Ensure we have at least 1000 users with the role 'candidate'
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
                    'password'          => Hash::make('password'), // default password
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                $user->assignRole('candidate');
            }
        }

        // 2. Now get exactly 1000 candidates (or all if somehow more exist)
        $candidates = User::role('candidate')->take(1000)->get();

        $this->command->info("Adding contact details for {$candidates->count()} candidates...");

        foreach ($candidates as $user) {
            $postal = $faker->streetAddress . ",\n" .
                      $faker->city . ", " . $faker->stateAbbr . ", Pakistan";

            $sameAsPostal = $faker->boolean(80); // 80% chance same as postal

            DB::table('user_contacts')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'phone'            => $faker->boolean(60)
                        ? '05' . $faker->randomElement(['1','2','3','4','5','6','7','8','9']) . $faker->numerify('#######')
                        : null,
                    'mobile'           => '03' . $faker->randomElement(['0','1','2','3','4']) . $faker->numerify('#######'),
                    'postal_address'   => $faker->streetAddress . ",\n" . $faker->city . ", " . $faker->stateAbbr . ", Pakistan",
                    'permanent_address'=> $sameAsPostal ? $postal : $faker->streetAddress . ",\n" . $faker->city . ", Pakistan",
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            );
        }

        $this->command->info("Successfully added contact details for 1000 candidates! 🎉");
    }
}
