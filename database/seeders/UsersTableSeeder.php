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
                'over_name' => '山下', 'under_name' => '幸輝',
                'over_name_kana' => 'ヤマシタ', 'under_name_kana' => 'コウキ',
                'mail_address' => 'koki@gmail.com',
                'sex' => 1,
                'birth_day' => Carbon::create(2003, 11, 7),
                'role' => 1,
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
            ],
        ];
        DB::table('users')->insert($users);

    }
}
