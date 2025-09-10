<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            // Primary (stage = 0) Classes I - V
            ['stage' => 0, 'stream_id' => null, 'name' => 'English', 'code' => 'ENG'],
            ['stage' => 0, 'stream_id' => null, 'name' => 'Hindi', 'code' => 'HIN'],
            ['stage' => 0, 'stream_id' => null, 'name' => 'Mathematics', 'code' => 'MATH'],
            ['stage' => 0, 'stream_id' => null, 'name' => 'Environmental Studies (EVS)', 'code' => 'EVS'],
            ['stage' => 0, 'stream_id' => null, 'name' => 'Art & Craft', 'code' => 'ART'],
            ['stage' => 0, 'stream_id' => null, 'name' => 'Physical Education', 'code' => 'PE'],
            ['stage' => 0, 'stream_id' => null, 'name' => 'General Knowledge / Moral Science', 'code' => 'GK'],

            // Secondary (stage = 1) Classes VI - VIII
            ['stage' => 1, 'stream_id' => null, 'name' => 'English', 'code' => 'ENG'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Hindi', 'code' => 'HIN'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Mathematics', 'code' => 'MATH'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Science', 'code' => 'SCI'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Social Science (History/Civics/Geography)', 'code' => 'SOC'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Computer Science / IT', 'code' => 'CS'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Sanskrit / Third Language', 'code' => 'LANG'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Art & Craft', 'code' => 'ART'],
            ['stage' => 1, 'stream_id' => null, 'name' => 'Physical Education', 'code' => 'PE'],

            // Senior Secondary (stage = 2) — Science stream (stream_id = 1)
            ['stage' => 2, 'stream_id' => 1, 'name' => 'English Core', 'code' => 'ENG'],
            ['stage' => 2, 'stream_id' => 1, 'name' => 'Physics', 'code' => 'PHY'],
            ['stage' => 2, 'stream_id' => 1, 'name' => 'Chemistry', 'code' => 'CHE'],
            ['stage' => 2, 'stream_id' => 1, 'name' => 'Mathematics', 'code' => 'MATH'],
            ['stage' => 2, 'stream_id' => 1, 'name' => 'Biology', 'code' => 'BIO'],
            ['stage' => 2, 'stream_id' => 1, 'name' => 'Computer Science', 'code' => 'CS'],
            ['stage' => 2, 'stream_id' => 1, 'name' => 'Informatics Practices', 'code' => 'IP'],

            // Senior Secondary — Commerce stream (stream_id = 2)
            ['stage' => 2, 'stream_id' => 2, 'name' => 'English Core', 'code' => 'ENG'],
            ['stage' => 2, 'stream_id' => 2, 'name' => 'Accountancy', 'code' => 'ACC'],
            ['stage' => 2, 'stream_id' => 2, 'name' => 'Business Studies', 'code' => 'BUS'],
            ['stage' => 2, 'stream_id' => 2, 'name' => 'Economics', 'code' => 'ECO'],
            ['stage' => 2, 'stream_id' => 2, 'name' => 'Mathematics', 'code' => 'MATH'],
            ['stage' => 2, 'stream_id' => 2, 'name' => 'Informatics Practices', 'code' => 'IP'],

            // Senior Secondary — Humanities stream (stream_id = 3)
            ['stage' => 2, 'stream_id' => 3, 'name' => 'English Core', 'code' => 'ENG'],
            ['stage' => 2, 'stream_id' => 3, 'name' => 'History', 'code' => 'HIS'],
            ['stage' => 2, 'stream_id' => 3, 'name' => 'Political Science', 'code' => 'POL'],
            ['stage' => 2, 'stream_id' => 3, 'name' => 'Geography', 'code' => 'GEO'],
            ['stage' => 2, 'stream_id' => 3, 'name' => 'Sociology', 'code' => 'SOC'],
            ['stage' => 2, 'stream_id' => 3, 'name' => 'Psychology', 'code' => 'PSY'],
            ['stage' => 2, 'stream_id' => 3, 'name' => 'Economics', 'code' => 'ECO'],

            // Senior Secondary — Skill Electives (stream_id = 4)
            ['stage' => 2, 'stream_id' => 4, 'name' => 'Fitness & Wellness', 'code' => 'FW'],
            ['stage' => 2, 'stream_id' => 4, 'name' => 'Retail & Marketing', 'code' => 'RM'],
            ['stage' => 2, 'stream_id' => 4, 'name' => 'Beauty & Wellness', 'code' => 'BW'],
            ['stage' => 2, 'stream_id' => 4, 'name' => 'Food Production / Home Science', 'code' => 'HP'],
            ['stage' => 2, 'stream_id' => 4, 'name' => 'Financial Market Management', 'code' => 'FMM'],
        ];

        // add timestamps and insert
        $now = now();
        $data = array_map(function ($s) use ($now) {
            $s['created_at'] = $now;
            $s['updated_at'] = $now;
            return $s;
        }, $subjects);

        DB::table('subjects')->insert($data);
    }
}
