<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReserveSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reserve_settings')->truncate();

        $reserveSettings = [];
        $limit = 20;

        $date = Carbon::today();

        $lastDayOfMonth = $date->copy()->endOfMonth();

        while ($date->lte($lastDayOfMonth)) {
            for ($part = 1; $part <= 3; $part++) {
                $reserveSettings[] = [
                    'setting_reserve' => $date->format('Y-m-d'),
                    'setting_part' => $part,
                    'limit_users' => $limit,
                    'created_at' => Carbon::now(),
                ];
            }

            $date->addDay();
        }

        DB::table('reserve_settings')->insert($reserveSettings);
    }
}
