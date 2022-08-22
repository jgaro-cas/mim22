<?php

use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\VolunteerWorkblockController;
use App\Http\Controllers\WorkBlockController;
use App\Http\Controllers\WorkplaceController;
use App\Models\Volunteer;
use App\Models\Workplace;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

//Route::resource('/volunteers', VolunteerController::class);

Route::middleware(['auth'])->group(function(){
    Route::resource('/workplaces', WorkplaceController::class);
    Route::get('/allWorkplaces', [WorkplaceController::class, 'fullIndexJson']);
    Route::resource('/workblocks', WorkBlockController::class);
    Route::get('admin/volunteers', [VolunteerController::class, 'index']);
    Route::get('/admin/volunteers/{volunteer}', [VolunteerController::class, 'show']);
    Route::get('/admin', [WorkplaceController::class, 'index'])->name('admin.index');
    Route::delete('/admin/delete_vol_wb/{wbId}/{volId}', [VolunteerWorkblockController::class, 'destroy']);
    Route::delete('/volunteers/{volunteer}', [VolunteerController::class, 'destroy']);
    Route::put('/volunteers/{volunteer}', [VolunteerController::class, 'update']);
    Route::post('/admin/volunteers/to-excel', [VolunteerController::class, 'getExcel']);

});

Route::post('/volunteers', [VolunteerController::class, 'store']);

Route::get('/', function(){
    //echo(Storage::url('Festicheyres2022_banner.jpeg'));
    $workplaces = Workplace::with(['workblocks' => function($query){
        $query->withCount('volunteers');
    }])->get();

    $filtered = $workplaces->where('workblocks', '!=', '[]');

    return Inertia::render('Registration', [
        'workplaces' => $filtered->all()
    ]);
});

Route::get('/merci_beaucoup/{mail}', function($mail){
    return Inertia::render('Registered',[
        'mail' => $mail
    ]);
})->name('thankyou');
