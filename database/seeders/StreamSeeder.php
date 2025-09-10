<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StreamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('streams')->insert([
            ['name' => 'Science', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Commerce', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Humanities', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Skill Electives', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
