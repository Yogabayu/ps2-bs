<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            [
                'code' => 101,
                'name' => 'Kantor Pusat Caruban'
            ],
            [
                'code' => 201,
                'name' => 'Kantor Cabang Ponorogo'
            ],
            [
                'code' => 301,
                'name' => 'Kantor Cabang Madiun'
            ],
            [
                'code' => 401,
                'name' => 'Kantor Cabang Magetan'
            ],
        ]);

        DB::table('place_transcs')->insert([
            [
                'code' => 'W',
                'name' => 'WALK IN'
            ],
            [
                'code' => 'AO',
                'name' => 'SETORAN AO'
            ],
        ]);

        DB::table('positions')->insert([
            [
                'name' => 'Administrator'
            ],
            [
                'name' => 'SPV OPS'
            ],
            [
                'name' => 'Teller'
            ],
            [
                'name' => 'Customer Service'
            ],
            [
                'name' => 'Admin Operasional'
            ],
        ]);

        DB::table('users')->insert([
            [
                'uuid' => Str::uuid(),
                'nik' => '008220353',
                'name' => 'YogaBAP',
                'photo' => 'avatar-1.png',
                'email' => 'yogabayusbi@gmail.com',
                'password' => Hash::make('12345678'),
                'office_id' => 1,
                'position_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'nik' => '008123456',
                'name' => 'example SPV',
                'photo' => 'avatar-2.png',
                'email' => 'spv@gmail.com',
                'password' => Hash::make('12345678'),
                'office_id' => 1,
                'position_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'nik' => '008789012',
                'name' => 'example Teller',
                'photo' => 'avatar-3.png',
                'email' => 'teller@gmail.com',
                'password' => Hash::make('12345678'),
                'office_id' => 1,
                'position_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('settings')->insert([
            [
                'logo' => 'logos.png',
                'name_app' => 'CepatCatat',
            ],
        ]);
    }
}
