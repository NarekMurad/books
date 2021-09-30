<?php


namespace App\Services;


use App\Models\Publisher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    /**
     * @param array $data
     * @param string $publisher
     */
    public function createUser(array $data, string $publisher) : void
    {
        $data['password'] = Hash::make($data['password']);
        $data['token'] = Str::random(150);
        $user = User::create($data);
        if ($user) {
            Publisher::create(['name' => $publisher]);
        }
    }
}
