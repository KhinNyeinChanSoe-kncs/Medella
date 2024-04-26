<?php

namespace Database\Seeders;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
                'id' => 1,
                'name' => 'Administration',
                'phone' => '09856639636',
                'email' => 'admin.medella@yopmail.com',
                'description' => 'description',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'HR',
                'phone' => '09785563269',
                'email' => 'hr.medella@yopmail.com',
                'description' => 'description',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Nurse',
                'phone' => '062399874526',
                'email' => 'nurse.medella@yopmail.com',
                'description' => 'description',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Doctor',
                'phone' => '085236974512',
                'email' => 'doctor.medella@yopmail.com',
                'description' => 'description',
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
