<?php

use App\Models\Submission;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::inertia('/', 'Welcome')->name('welcome');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::inertia('/addresses', 'Addresses')->name('addresses');
Route::inertia('/websites', 'Websites')->name('websites');

Route::prefix('submissions')->name('submissions')->group(function () {
    Route::inertia('/', 'Submission/Index');
    Route::inertia('/create', 'Submission/Create')->name('.create');

    Route::get('/{submission}', function (Submission $submission) {
        $statusNames = Submission::STATUS_NAMES;

        return Inertia::render('Submission/Show', compact('submission', 'statusNames'));
    })->name('.show');
});
