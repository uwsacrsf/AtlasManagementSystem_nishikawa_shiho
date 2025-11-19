<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sub_categories')->truncate();

        $kyouka_id = DB::table('main_categories')->where('main_category', '教科')->value('id');
        $sansyo_id = DB::table('main_categories')->where('main_category', '参考書')->value('id');

        $sub_categories = [];

        if ($kyouka_id) {
            $kyouka_subjects = ['英語', '国語', '数学'];
            foreach ($kyouka_subjects as $subject) {
                $sub_categories[] = [
                    'main_category_id' => $kyouka_id,
                    'sub_category' => $subject,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
        }

        if ($sansyo_id) {
            $sansyo_subjects = ['英単語帳', '数学参考書'];
            foreach ($sansyo_subjects as $subject) {
                $sub_categories[] = [
                    'main_category_id' => $sansyo_id,
                    'sub_category' => $subject,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
        }
        DB::table('sub_categories')->insert($sub_categories);
    }
}
