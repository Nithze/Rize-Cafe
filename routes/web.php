<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// route::get('admin/admin', [AdminController::class, 'index'])->middleware(['auth', 'admin', 'wez']);

Route::prefix('admin')->middleware(['auth', 'admin', 'wez'])->group(function () {
    Route::get('admin', [AdminController::class, 'index']);
    Route::post('/add-user', [AdminController::class, 'store'])->name('addUser');
    Route::delete('/delete-user/{id}', [AdminController::class, 'destroy'])->name('deleteUser');
    Route::put('/edit-user', [AdminController::class, 'update'])->name('editUser');
});





// Grouping routes for cashier
Route::prefix('cashier')->middleware(['auth', 'cashier', 'wez'])->group(function () {
    Route::get('cashier', [CashierController::class, 'cashier']);
    // Route::get('transactions', [CashierController::class, 'transactions']);
    Route::get('transactions', [CashierController::class, 'showTransactions'])->name('transactions');
    Route::get('/transactions/{id}', [CashierController::class, 'show']);
    Route::put('/change-table-status/{id}', [CashierController::class, 'changeTableStatus'])->name('changeTableStatus');
    // Route::put('/change-table-status/{id}', 'cashierController@changeTableStatus')->name('changeTableStatus');


});
// Grouping routes for manager
Route::prefix('manager')->middleware(['auth', 'manager', 'wez'])->group(function () {
    Route::get('manager', [ManagerController::class, 'manager']);
    Route::get('managerlog', [ManagerController::class, 'managerlog']);
    Route::get('managerproduk', [ManagerController::class, 'managerproduk',]);
    Route::get('managertransactions', [ManagerController::class, 'managertransactions', 'showTransactions']);
    Route::post('add-menu', [ManagerController::class, 'addMenu'])->name('addMenu');
    // Route::delete('menu/{id}', [ManagerController::class, 'deleteMenu'])->name('deleteMenu');
    Route::delete('/menu/{id}', [ManagerController::class, 'deleteMenu'])->name('deleteMenu');
    Route::get('/getMenu/{id}', [ManagerController::class, 'getMenu']);
    Route::post('/editMenu/{id}', [ManagerController::class, 'editMenu']);

});




use App\Http\Controllers\TransactionController;

// Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
