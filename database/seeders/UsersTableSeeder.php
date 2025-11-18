<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'over_name' => '西川', 'under_name' => '詩歩',
                'over_name_kana' => 'ニシカワ', 'under_name_kana' => 'シホ',
                'mail_address' => 'shiho@gmail.com',
                'sex' => 2,
                'birth_day' => Carbon::create(1997, 10, 22),
                'role' => 4,
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
            ],
            [
                'over_name' => '星野', 'under_name' => '源',
                'over_name_kana' => 'ホシノ', 'under_name_kana' => 'ゲン',
                'mail_address' => 'gen@gmail.com',
                'sex' => 1,
                'birth_day' => Carbon::create(1991, 1, 28),
                'role' => 1,
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
            ],
        ];
        DB::table('users')->insert($users);

    }
}
