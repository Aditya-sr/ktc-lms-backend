<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // === ADMINS ===
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'name' => "Admin $i",
                'email' => "admin$i@example.com",
                'password' => Hash::make('1234567890'),
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // === TEACHERS ===
        for ($i = 1; $i <= 3; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => "Teacher $i",
                'email' => "teacher$i@example.com",
                'password' => Hash::make('123456789'),
                'role' => 'teacher',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            DB::table('teacher_details')->insert([
                'user_id' => $userId,
                'employee_id' => "EMP00$i",
                'date_of_joining' => $now->toDateString(),
                'qualification' => 'M.Ed',
                'phone' => "99999999$i",
                'address' => "Teacher Address $i",
                'city' => "City $i",
                'state' => "State $i",
                'pincode' => "12345$i",
                'emergency_contact' => "88888888$i",
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // === STUDENTS ===
        for ($i = 1; $i <= 3; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => "Student $i",
                'email' => "student$i@example.com",
                'password' => Hash::make('123456789'),
                'role' => 'user',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            DB::table('student_details')->insert([
                'user_id' => $userId,
                'standard_id' => 1,
                'section_id' => 1,
                'full_name' => "Student $i",
                'father_name' => "Father $i",
                'guardian_relation' => 'Father',
                'guardian_phone' => "77777777$i",
                'guardian_mail' => "parent$i@example.com",
                'guardian_occupation' => 'Private',
                'mother_name' => "Mother $i",
                'email' => "student$i@example.com",
                'dob' => '2005-01-01',
                'gender' => 'Male',
                'nationality' => 'Indian',
                'blood_group' => 'A+',
                'local_address' => 'Local Address',
                'permanent_address' => 'Permanent Address',
                'city' => 'City',
                'state' => 'State',
                'pincode' => '100001',
                'admission_no' => "ADM00$i",
                'date_of_admission' => '2020-06-01',
                'roll_no' => "10$i",
                'board' => 'CBSE',
                'aadhar_no' => "1234567890$i",
                'phone' => "66666666$i",
                'image' => null,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
