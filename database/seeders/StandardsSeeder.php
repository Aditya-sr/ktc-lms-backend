<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StandardsSeeder extends Seeder
{
    public function run()
    {
        $standards = [
            // Primary (stage = 0)
            ['name' => 'Class I', 'code' => 'I', 'board' => 'CBSE', 'order' => 1],
            ['name' => 'Class II', 'code' => 'II', 'board' => 'CBSE', 'order' => 2],
            ['name' => 'Class III', 'code' => 'III', 'board' => 'CBSE', 'order' => 3],
            ['name' => 'Class IV', 'code' => 'IV', 'board' => 'CBSE', 'order' => 4],
            ['name' => 'Class V', 'code' => 'V', 'board' => 'CBSE', 'order' => 5],

            // Secondary (stage = 1)
            ['name' => 'Class VI', 'code' => 'VI', 'board' => 'CBSE', 'order' => 6],
            ['name' => 'Class VII', 'code' => 'VII', 'board' => 'CBSE', 'order' => 7],
            ['name' => 'Class VIII', 'code' => 'VIII', 'board' => 'CBSE', 'order' => 8],

            // Senior Secondary (stage = 2)
            ['name' => 'Class IX', 'code' => 'IX', 'board' => 'CBSE', 'order' => 9],
            ['name' => 'Class X', 'code' => 'X', 'board' => 'CBSE', 'order' => 10],
            ['name' => 'Class XI', 'code' => 'XI', 'board' => 'CBSE', 'order' => 11],
            ['name' => 'Class XII', 'code' => 'XII', 'board' => 'CBSE', 'order' => 12],
        ];

        $now = now();
        $data = array_map(function ($s) use ($now) {
            $s['organization_id'] = 0; // default, update if needed
            $s['file_path'] = null;
            $s['is_active'] = true;
            $s['created_at'] = $now;
            $s['updated_at'] = $now;
            return $s;
        }, $standards);

        DB::table('standards')->insert($data);
    }
}
