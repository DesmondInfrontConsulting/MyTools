<?php

use App\Livewire\MyKadGenerator;
use App\Livewire\PassportGenerator;
use Illuminate\Support\Facades\Route;


Route::get('/mykad', MyKadGenerator::class)->name('mykad');
Route::get('/passport', PassportGenerator::class)->name('passport');

Route::get('/', fn() => redirect()->route('mykad'));
