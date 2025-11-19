<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MainCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_categories')->truncate();

        $categories = [
            ['main_category' => '教科', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['main_category' => '参考書', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('main_categories')->insert($categories);
    }
}
