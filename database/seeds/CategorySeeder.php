<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Bus',
                'slug' => 'bus',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Van',
                'slug' => 'van',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Car',
                'slug' => 'car',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
