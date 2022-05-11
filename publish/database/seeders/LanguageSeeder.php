<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('languages_flags')->insert([
            'name' => config('translatable.locales.fr'),
            'flag' => 'fr',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

