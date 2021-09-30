<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Services\UserService;

class RegisterController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('register');
    }

    /**
     * @param RegistrationRequest $request
     * @param UserService $userService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegistrationRequest $request, UserService $userService)
    {
        $data = $request->only(['email', 'password']);
        $userService->createUser($data, $request->name);
        return redirect()->route('login');
    }
}
