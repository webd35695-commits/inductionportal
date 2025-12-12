<?php

namespace Database\Seeders;

use Database\Seeders\CandidateQualificationsSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate in reverse order to avoid FK errors
        DB::table('center_posts')->truncate();
        DB::table('centers')->truncate();
        DB::table('cities')->truncate();
        DB::table('districts')->truncate();
        DB::table('provinces')->truncate();
        DB::table('posts')->truncate();
        DB::table('induction_phases')->truncate();
        DB::table('qualification_types')->truncate();
        DB::table('post_categories')->truncate();

        $this->seedProvincesAndDistrictsAndCities();
        $this->seedInductionPhase();
        $this->seedQualificationAndPostCategories();
        $this->seedPosts();
        $this->seedCenters();
        $this->seedCenterPosts();

        $this->call(RolePermissionSeeder::class);
        $this->call(DummyApplicantsSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

   private function seedProvincesAndDistrictsAndCities()
{
    // === 1. Provinces ===
    $provinces = [
        'Punjab (Including Islamabad)',
        'Khyber Pakhtunkhwa',
        'Balochistan',
        'Sindh (Urban)',
        'Sindh (Rural)',
        'AJK',
        'FATA',
        'Gilgit Baltistan',
    ];

    $provinceMap = [];
    foreach ($provinces as $name) {
        $id = DB::table('provinces')->insertGetId([
            'name' => $name,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $provinceMap[$name] = $id;
    }

    // === 2. Districts + Cities (grouped by province) ===
    $data = [
        // Punjab (Including Islamabad)
        'Punjab (Including Islamabad)' => [
            'Attock' => ['Attock City'],
            'Rawalpindi' => ['Rawalpindi', 'Islamabad'],
            'Jhelum' => ['Jhelum'],
            'Chakwal' => ['Chakwal'],
            'Talagang' => ['Talagang'],
            'Gujrat' => ['Gujrat'],
            'Mandi Bahauddin' => ['Mandi Bahauddin'],
            'Hafizabad' => ['Hafizabad'],
            'Gujranwala' => ['Gujranwala'],
            'Wazirabad' => ['Wazirabad'],
            'Sialkot' => ['Sialkot'],
            'Narowal' => ['Narowal'],
            'Sheikhupura' => ['Sheikhupura'],
            'Nankana Sahib' => ['Nankana Sahib'],
            'Lahore' => ['Lahore'],
            'Kasur' => ['Kasur'],
            'Faisalabad' => ['Faisalabad'],
            'Chiniot' => ['Chiniot'],
            'Jhang' => ['Jhang'],
            'Toba Tek Singh' => ['Toba Tek Singh'],
            'Sargodha' => ['Sargodha'],
            'Khushab' => ['Khushab'],
            'Mianwali' => ['Mianwali'],
            'Bhakkar' => ['Bhakkar'],
            'Sahiwal' => ['Sahiwal'],
            'Okara' => ['Okara'],
            'Pakpattan' => ['Pakpattan'],
            'Multan' => ['Multan'],
            'Khanewal' => ['Khanewal'],
            'Vehari' => ['Vehari'],
            'Lodhran' => ['Lodhran'],
            'Bahawalpur' => ['Bahawalpur'],
            'Bahawalnagar' => ['Bahawalnagar'],
            'Rahim Yar Khan' => ['Rahim Yar Khan'],
            'Dera Ghazi Khan' => ['Dera Ghazi Khan'],
            'Layyah' => ['Layyah'],
            'Muzaffargarh' => ['Muzaffargarh'],
            'Rajanpur' => ['Rajanpur'],
            'Kot Addu' => ['Kot Addu'],
            'Taunsa' => ['Taunsa'],
        ],

        // Sindh (Urban)
        'Sindh (Urban)' => [
            'Karachi Central' => ['Karachi'],
            'Karachi East' => ['Karachi'],
            'Karachi West' => ['Karachi'],
            'Karachi South' => ['Karachi'],
            'Karachi Korangi' => ['Karachi'],
            'Karachi Malir' => ['Karachi'],
            'Karachi Keamari' => ['Karachi'],
            'Karachi North' => ['Karachi'],
        ],

        // Sindh (Rural)
        'Sindh (Rural)' => [
            'Hyderabad' => ['Hyderabad'],
            'Jamshoro' => ['Jamshoro'],
            'Tando Allahyar' => ['Tando Allahyar'],
            'Tando Muhammad Khan' => ['Tando Muhammad Khan'],
            'Matiari' => ['Matiari'],
            'Badin' => ['Badin'],
            'Thatta' => ['Thatta'],
            'Sujawal' => ['Sujawal'],
            'Dadu' => ['Dadu'],
            'Qamber Shahdadkot' => ['Qamber'],
            'Larkana' => ['Larkana'],
            'Shikarpur' => ['Shikarpur'],
            'Jacobabad' => ['Jacobabad'],
            'Kashmore' => ['Kashmore'],
            'Khairpur' => ['Khairpur'],
            'Sukkur' => ['Sukkur'],
            'Ghotki' => ['Ghotki'],
            'Shaheed Benazirabad' => ['Nawabshah'],
            'Naushahro Feroze' => ['Naushahro Feroze'],
            'Sanghar' => ['Sanghar'],
            'Mirpur Khas' => ['Mirpur Khas'],
            'Umerkot' => ['Umerkot'],
            'Tharparkar' => ['Mithi'],
        ],

        // Khyber Pakhtunkhwa
        'Khyber Pakhtunkhwa' => [
            'Peshawar' => ['Peshawar'],
            'Nowshera' => ['Nowshera'],
            'Charsadda' => ['Charsadda'],
            'Mardan' => ['Mardan'],
            'Swabi' => ['Swabi'],
            'Kohat' => ['Kohat'],
            'Karak' => ['Karak'],
            'Hangu' => ['Hangu'],
            'Bannu' => ['Bannu'],
            'Lakki Marwat' => ['Lakki Marwat'],
            'Dera Ismail Khan' => ['Dera Ismail Khan'],
            'Tank' => ['Tank'],
            'Abbottabad' => ['Abbottabad'],
            'Haripur' => ['Haripur'],
            'Mansehra' => ['Mansehra'],
            'Battagram' => ['Battagram'],
            'Torghar' => ['Torghar'],
            'Kolai Palas' => ['Kolai Palas'],
            'Lower Kohistan' => ['Pattan'],
            'Upper Kohistan' => ['Dassu'],
            'Swat' => ['Mingora'],
            'Buner' => ['Daggar'],
            'Shangla' => ['Alpurai'],
            'Malakand' => ['Batkhela'],
            'Lower Dir' => ['Timergara'],
            'Upper Dir' => ['Dir'],
            'Chitral' => ['Chitral'],
            'Lower Chitral' => ['Chitral'],
            'Upper Chitral' => ['Chitral'],
        ],

        // FATA (Merged Districts)
        'FATA' => [
            'Bajaur' => ['Khar'],
            'Mohmand' => ['Ghalanai'],
            'Khyber' => ['Landi Kotal'],
            'Orakzai' => ['Orakzai'],
            'Kurram' => ['Parachinar'],
            'North Waziristan' => ['Miranshah'],
            'South Waziristan' => ['Wana'],
        ],

        // Balochistan
        'Balochistan' => [
            'Quetta' => ['Quetta'],
            'Chaman' => ['Chaman'],
            'Pishin' => ['Pishin'],
            'Killa Abdullah' => ['Chaman'],
            'Killa Saifullah' => ['Killa Saifullah'],
            'Zhob' => ['Zhob'],
            'Sherani' => ['Sherani'],
            'Loralai' => ['Loralai'],
            'Duki' => ['Duki'],
            'Musakhel' => ['Musakhel'],
            'Barkhan' => ['Barkhan'],
            'Kohlu' => ['Kohlu'],
            'Dera Bugti' => ['Dera Bugti'],
            'Sibi' => ['Sibi'],
            'Ziarat' => ['Ziarat'],
            'Harnai' => ['Harnai'],
            'Nasirabad' => ['Nasirabad'],
            'Jafarabad' => ['Jafarabad'],
            'Jhal Magsi' => ['Jhal Magsi'],
            'Sohbatpur' => ['Sohbatpur'],
            'Kachhi' => ['Dhadar'],
            'Mastung' => ['Mastung'],
            'Kalat' => ['Kalat'],
            'Khuzdar' => ['Khuzdar'],
            'Lasbela' => ['Uthal'],
            'Awaran' => ['Awaran'],
            'Washuk' => ['Washuk'],
            'Kharan' => ['Kharan'],
            'Nushki' => ['Nushki'],
            'Chagai' => ['Dalbandin'],
            'Panjgur' => ['Panjgur'],
            'Kech' => ['Turbat'],
            'Gwadar' => ['Gwadar'],
        ],

        // AJK
        'AJK' => [
            'Muzaffarabad' => ['Muzaffarabad'],
            'Hattian' => ['Hattian Bala'],
            'Neelum' => ['Athmuqam'],
            'Mirpur' => ['Mirpur'],
            'Bhimber' => ['Bhimber'],
            'Kotli' => ['Kotli'],
            'Poonch' => ['Rawalakot'],
            'Haveli' => ['Haveli'],
            'Bagh' => ['Bagh'],
            'Sudhnuti' => ['Pallandri'],
        ],

        // Gilgit Baltistan
        'Gilgit Baltistan' => [
            'Gilgit' => ['Gilgit'],
            'Hunza' => ['Aliabad'],
            'Nagar' => ['Nagar'],
            'Ghizer' => ['Gahkuch'],
            'Gupis Yasin' => ['Gupis'],
            'Diamer' => ['Chilas'],
            'Astore' => ['Astore'],
            'Skardu' => ['Skardu'],
            'Shigar' => ['Shigar'],
            'Kharmang' => ['Kharmang'],
            'Roundu' => ['Roundu'],
            'Darel' => ['Darel'],
            'Tangir' => ['Tangir'],
        ],
    ];

    foreach ($data as $provinceName => $districts) {
        $provinceId = $provinceMap[$provinceName];

        foreach ($districts as $districtName => $cities) {
            $districtId = DB::table('districts')->insertGetId([
                'province_id' => $provinceId,
                'name' => $districtName,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cities as $cityName) {
                DB::table('cities')->insert([
                    'district_id' => $districtId,
                    'name' => $cityName,
                    'test_center' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

    private function seedInductionPhase()
    {
        DB::table('induction_phases')->insert([
            'title' => 'Phase 1 - 2025',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function seedQualificationAndPostCategories()
    {
        // Qualification Types
        DB::table('qualification_types')->insert([
            ['name' => 'Master', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bachelor', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Post Categories
        DB::table('post_categories')->insert([
            ['name' => 'Teaching', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non-Teaching', 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedPosts()
    {
        $inductionPhaseId = DB::table('induction_phases')->first()->id;
        $qualificationTypeId = DB::table('qualification_types')->where('name', 'Master')->first()->id;
        $postCategoryId = DB::table('post_categories')->where('name', 'Teaching')->first()->id;

        $posts = [
            ['name' => 'Lecturer', 'abbr' => 'LEC', 'bps' => 17, 'min_age' => 21, 'max_age' => 35],
            ['name' => 'Assistant Professor', 'abbr' => 'AP', 'bps' => 19, 'min_age' => 25, 'max_age' => 40],
            ['name' => 'Trained Graduate Teacher', 'abbr' => 'TGT', 'bps' => 16, 'min_age' => 20, 'max_age' => 35],
            ['name' => 'Assistant', 'abbr' => 'Assistant', 'bps' => 15, 'min_age' => 18, 'max_age' => 30],
            ['name' => 'Lower Division Clerk', 'abbr' => 'LDC', 'bps' => 9, 'min_age' => 18, 'max_age' => 30],
        ];

        foreach ($posts as $post) {
            DB::table('posts')->insert([
                'induction_phase_id' => $inductionPhaseId,
                'post_category_id' => $postCategoryId,
                'qualification_type_id' => $qualificationTypeId,
                'name' => $post['name'],
                'post_abbreviation' => $post['abbr'],
                'number_post' => 100,
                'fee_post' => 800.00,
                'min_age' => $post['min_age'],
                'max_age' => $post['max_age'],
                'bps' => $post['bps'],
                'post_gender' => 'Both',
                'degree_required' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

   private function seedCenters()
{
    $centers = [
        // Islamabad / Rawalpindi Region
        ['name' => 'F.G. Sir Syed College, The Mall, Rawalpindi Cantt', 'code' => 'RWP01', 'address' => 'Opposite State Bank, The Mall, Rawalpindi Cantt', 'city' => 'Rawalpindi'],
        ['name' => 'F.G. Degree College for Women, Kashmir Road, Rawalpindi Cantt', 'code' => 'RWP02', 'address' => 'Kashmir Road, Near Army Medical College, Rawalpindi', 'city' => 'Rawalpindi'],
        ['name' => 'F.G. Quaid-e-Azam College, Chaklala Scheme III, Rawalpindi', 'code' => 'RWP03', 'address' => 'Chaklala Scheme III, Rawalpindi Cantt', 'city' => 'Rawalpindi'],
        ['name' => 'Islamabad Model College for Boys F-8/4, Islamabad', 'code' => 'ISB01', 'address' => 'F-8/4, Near F.G. College, Islamabad', 'city' => 'Islamabad'],
        ['name' => 'Islamabad Model Postgraduate College H-8, Islamabad', 'code' => 'ISB02', 'address' => 'H-8/1, Islamabad', 'city' => 'Islamabad'],

        // Lahore Region
        ['name' => 'F.G. Degree College for Women, Lahore Cantt', 'code' => 'LHR01', 'address' => 'Sarwar Road, Lahore Cantt', 'city' => 'Lahore'],
        ['name' => 'F.G. Sir Syed College, Lahore Cantt', 'code' => 'LHR02', 'address' => 'Mazhar Lines, Lahore Cantt', 'city' => 'Lahore'],
        ['name' => 'F.G. Quaid-e-Azam Degree College, Aziz Bhatti Town, Lahore', 'code' => 'LHR03', 'address' => 'Allama Iqbal Road, Lahore Cantt', 'city' => 'Lahore'],
        ['name' => 'Govt. Islamia College Civil Lines, Lahore', 'code' => 'LHR04', 'address' => 'Civil Lines, Near Lahore High Court', 'city' => 'Lahore'],

        // Karachi Region
        ['name' => 'F.G. Boys Inter College, Karachi Cantt', 'code' => 'KHI01', 'address' => 'Near Cantt Railway Station, Karachi', 'city' => 'Karachi'],
        ['name' => 'F.G. Girls Inter College, Askari-III, Karachi Cantt', 'code' => 'KHI02', 'address' => 'Askari-III, Near Jinnah Hospital, Karachi Cantt', 'city' => 'Karachi'],
        ['name' => 'F.G. Minwala Public School, Karachi Cantt', 'code' => 'KHI03', 'address' => 'Opposite Askari-II, Karachi Cantt', 'city' => 'Karachi'],
        ['name' => 'F.G. Abbysinia Public School, Karachi Cantt', 'code' => 'KHI04', 'address' => 'Near Cantt Station, Karachi', 'city' => 'Karachi'],

        // Punjab (Other Major Cities)
        ['name' => 'F.G. Degree College, Faisalabad Cantt', 'code' => 'FSD01', 'address' => 'Near Jinnah Colony, Faisalabad', 'city' => 'Faisalabad'],
        ['name' => 'F.G. College for Women, Multan Cantt', 'code' => 'MUL01', 'address' => 'Near Bijli Ghar Chowk, Multan Cantt', 'city' => 'Multan'],
        ['name' => 'F.G. Public School No.1 (Boys), Multan Cantt', 'code' => 'MUL02', 'address' => 'Saddar Bazar, Multan Cantt', 'city' => 'Multan'],
        ['name' => 'F.G. Degree College, Gujranwala Cantt', 'code' => 'GRW01', 'address' => 'Gujranwala Cantt', 'city' => 'Gujranwala'],
        ['name' => 'F.G. College, Sialkot Cantt', 'code' => 'SKT01', 'address' => 'Khayaban-e-Iqbal, Sialkot Cantt', 'city' => 'Sialkot'],

        // Khyber Pakhtunkhwa
        ['name' => 'F.G. Degree College for Men, Peshawar Cantt', 'code' => 'PSH01', 'address' => 'Opposite Fauji Foundation Hospital, Peshawar Cantt', 'city' => 'Peshawar'],
        ['name' => 'F.G. Degree College for Women, Mall Road, Peshawar Cantt', 'code' => 'PSH02', 'address' => 'Mall Road, Peshawar Cantt', 'city' => 'Peshawar'],
        ['name' => 'F.G. Public School (Boys), Khyber Road, Peshawar Cantt', 'code' => 'PSH03', 'address' => 'Khyber Road, Peshawar Cantt', 'city' => 'Peshawar'],
        ['name' => 'F.G. College, Abbottabad Cantt', 'code' => 'ABB01', 'address' => 'Abbottabad Cantt', 'city' => 'Abbottabad'],

        // Balochistan
        ['name' => 'F.G. Degree College, Quetta Cantt', 'code' => 'QTA01', 'address' => 'Madrassa Road, Quetta Cantt', 'city' => 'Quetta'],
        ['name' => 'F.G. Public School (Boys), Quetta Cantt', 'code' => 'QTA02', 'address' => 'Near Command & Staff College, Quetta', 'city' => 'Quetta'],

        // Other Regions
        ['name' => 'F.G. College, Hyderabad Cantt', 'code' => 'HYD01', 'address' => 'Hyderabad Cantt', 'city' => 'Hyderabad'],
        ['name' => 'F.G. College, Sukkur', 'code' => 'SUK01', 'address' => 'Military Road, Sukkur', 'city' => 'Sukkur'],
    ];

    foreach ($centers as $center) {
        $cityId = DB::table('cities')
            ->where('name', 'LIKE', '%' . $center['city'] . '%')
            ->orWhere('name', $center['city'])
            ->value('id');

        if ($cityId) {
            DB::table('centers')->insert([
                'name'       => $center['name'],
                'code'       => $center['code'],
                'address'    => $center['address'],
                'city_id'    => $cityId,
                'capacity'   => 300,
                'is_active'  => 1,
                'status'     => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

    private function seedCenterPosts()
{
    // Fetch actual IDs using the real center names
    $centerIds = DB::table('centers')->pluck('id', 'name');
    $postIds   = DB::table('posts')->pluck('id', 'name');

    $mappings = [
        // === RAWALPINDI / ISLAMABAD Region (High Demand) ===
        ['center' => 'F.G. Sir Syed College, The Mall, Rawalpindi Cantt', 'post' => 'Lecturer', 'max' => 350 ],
        ['center' => 'F.G. Sir Syed College, The Mall, Rawalpindi Cantt', 'post' => 'Assistant Professor', 'max' => 180],
        ['center' => 'F.G. Sir Syed College, The Mall, Rawalpindi Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 420],
        ['center' => 'F.G. Sir Syed College, The Mall, Rawalpindi Cantt', 'post' => 'Assistant', 'max' => 250],
        ['center' => 'F.G. Sir Syed College, The Mall, Rawalpindi Cantt', 'post' => 'Lower Division Clerk', 'max' => 300],

        ['center' => 'Islamabad Model Postgraduate College H-8, Islamabad', 'post' => 'Lecturer', 'max' => 380],
        ['center' => 'Islamabad Model Postgraduate College H-8, Islamabad', 'post' => 'Assistant Professor', 'max' => 200],
        ['center' => 'Islamabad Model Postgraduate College H-8, Islamabad', 'post' => 'Trained Graduate Teacher', 'max' => 450],

        // === LAHORE (Biggest Hub) ===
        ['center' => 'F.G. Degree College for Women, Lahore Cantt', 'post' => 'Lecturer', 'max' => 500],
        ['center' => 'F.G. Degree College for Women, Lahore Cantt', 'post' => 'Assistant Professor', 'max' => 280],
        ['center' => 'F.G. Degree College for Women, Lahore Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 600],

        ['center' => 'F.G. Quaid-e-Azam Degree College, Aziz Bhatti Town, Lahore', 'post' => 'Lecturer', 'max' => 480],
        ['center' => 'F.G. Quaid-e-Azam Degree College, Aziz Bhatti Town, Lahore', 'post' => 'Assistant Professor', 'max' => 250],
        ['center' => 'F.G. Quaid-e-Azam Degree College, Aziz Bhatti Town, Lahore', 'post' => 'Trained Graduate Teacher', 'max' => 580],

        // === KARACHI (High Volume) ===
        ['center' => 'F.G. Boys Inter College, Karachi Cantt', 'post' => 'Lecturer', 'max' => 420],
        ['center' => 'F.G. Boys Inter College, Karachi Cantt', 'post' => 'Assistant Professor', 'max' => 220],
        ['center' => 'F.G. Boys Inter College, Karachi Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 550],
        ['center' => 'F.G. Boys Inter College, Karachi Cantt', 'post' => 'Lower Division Clerk', 'max' => 400],

        ['center' => 'F.G. Girls Inter College, Askari-III, Karachi Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 520],

        // === PESHAWAR ===
        ['center' => 'F.G. Degree College for Men, Peshawar Cantt', 'post' => 'Lecturer', 'max' => 320],
        ['center' => 'F.G. Degree College for Men, Peshawar Cantt', 'post' => 'Assistant Professor', 'max' => 160],
        ['center' => 'F.G. Degree College for Men, Peshawar Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 400],

        // === QUETTA ===
        ['center' => 'F.G. Degree College, Quetta Cantt', 'post' => 'Lecturer', 'max' => 280],
        ['center' => 'F.G. Degree College, Quetta Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 350],

        // === MULTAN ===
        ['center' => 'F.G. College for Women, Multan Cantt', 'post' => 'Lecturer', 'max' => 300],
        ['center' => 'F.G. College for Women, Multan Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 380],

        // === FAISALABAD ===
        ['center' => 'F.G. Degree College, Faisalabad Cantt', 'post' => 'Lecturer', 'max' => 340],
        ['center' => 'F.G. Degree College, Faisalabad Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 420],

        // === GUJRANWALA & SIALKOT (Growing Hubs) ===
        ['center' => 'F.G. Degree College, Gujranwala Cantt', 'post' => 'Lecturer', 'max' => 300],
        ['center' => 'F.G. College, Sialkot Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 360],

        // === HYDERABAD & SUKKUR ===
        ['center' => 'F.G. College, Hyderabad Cantt', 'post' => 'Trained Graduate Teacher', 'max' => 320],
        ['center' => 'F.G. College, Sukkur', 'post' => 'Lecturer', 'max' => 250],
    ];

    foreach ($mappings as $map) {
        // Safety check in case center/post name doesn't match exactly
        if (isset($centerIds[$map['center']]) && isset($postIds[$map['post']])) {
            DB::table('center_posts')->insert([
                'center_id'       => $centerIds[$map['center']],
                'post_id'         => $postIds[$map['post']],
                'max_applicants'  => $map['max'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
}
