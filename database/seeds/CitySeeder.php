<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert(
            [
                'name' => 'Hyderabad',
                'slug' => 'hyderabad',
                'latitude' => 25.3835549,
                'longitude' => 68.2968658,
                'radius' => 292,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
