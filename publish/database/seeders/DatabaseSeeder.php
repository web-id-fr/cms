<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory([
            'name' => 'Admin^ID',
            'email' => 'team@web-id.fr',
            'password' => Hash::make('demo@webid'),
        ])->create();

        DB::table('languages_flags')->insert([
            'name' => config('translatable.locales.fr'),
            'flag' => 'fr',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

