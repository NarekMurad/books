<?php

namespace Database\Seeders;

use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublishersSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['email' => 'mann@gmail.com', 'password' => Hash::make('123456'), 'token' => Str::random(150)]);
        User::create(['email' => 'alpina@gmail.com', 'password' => Hash::make('123456'), 'token' => Str::random(150)]);
        User::create(['email' => 'ekmos@gmail.com', 'password' => Hash::make('123456'), 'token' => Str::random(150)]);
        Publisher::create(['name' => 'Манн, Иванов и Фербер', 'user_id' => 1]);
        Publisher::create(['name' => 'Альпина Паблишер', 'user_id' => 2]);
        Publisher::create(['name' => 'Эксмо', 'user_id' => 3]);
    }
}
