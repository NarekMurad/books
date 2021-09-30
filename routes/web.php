<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BooksController::class, 'index'])->name('home');
Route::get('/book/create', [BooksController::class, 'create'])->name('book.create');
Route::get('/books/{book}/edit', [BooksController::class, 'edit'])->name('book.edit');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/login', [LoginController::class, 'signin'])->name('signin');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('registration');
