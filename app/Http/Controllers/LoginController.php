<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('login');
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signin(LoginRequest $request)
    {
        $data = $request->only(['email', 'password']);
        $user = User::where('email', $data['email'])->first();
        if ($user && Hash::check($data['password'], $user->password)) {
            Auth::attempt($data);
            return redirect()->route('home');
        }
        return redirect()->back(302);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
