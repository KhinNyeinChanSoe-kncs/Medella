<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clinics')->insert([
            [
                'name' => 'Paris',
                'phone' => '09856639636',
                'email' => 'paris.medella@yopmail.com',
                'description' => Str::random(10),
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'London',
                'phone' => '032569987456',
                'email' => 'london.medella@yopmail.com',
                'description' => Str::random(10),

                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Tokyo',
                'phone' => '09856639656',
                'email' => 'tokyo.medella@yopmail.com',
                'description' => Str::random(10),
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Syndney',
                'phone' => '09956639636',
                'email' => 'syndney.medella@yopmail.com',
                'description' => Str::random(10),
                'created_at' => Carbon::now(),
            ]
            ]);
    }
}
