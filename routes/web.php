<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('backend.admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');


    Route::get('/manager/dashboard', function () {
        return view('backend.manager.dashboard');
    })->middleware('role:manager')->name('manager.dashboard');


    Route::get('/cashier/dashboard', function () {
        return view('backend.cashier.dashboard');
    })->middleware('role:cashier')->name('cashier.dashboard');


    Route::get('/staff/dashboard', function () {
        return view('backend.staff.dashboard');
    })->middleware('role:staff')->name('staff.dashboard');
    // Category
    Route::get('/categories', [CategoryController::class, 'index']) ->middleware('role:admin,manager')->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create']) ->middleware('role:admin,manager')->name('categories.create');
    Route::post('/categories/store', [CategoryController::class, 'store']) ->middleware('role:admin,manager')->name('categories.store');
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit']) ->middleware('role:admin,manager')->name('categories.edit');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update']) ->middleware('role:admin,manager')->name('categories.update');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'delete']) ->middleware('role:admin,manager')->name('categories.delete');
    // Brand
    Route::get('/brands', [BrandController::class, 'index']) ->middleware('role:admin,manager')->name('brands');
    Route::get('/brands/create', [BrandController::class, 'create']) ->middleware('role:admin,manager')->name('brands.create');
    Route::post('/brands/store', [BrandController::class, 'store']) ->middleware('role:admin,manager')->name('brands.store');
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit']) ->middleware('role:admin,manager')->name('brands.edit');
    Route::post('/brands/update/{id}', [BrandController::class, 'update']) ->middleware('role:admin,manager')->name('brands.update');
    Route::get('/brands/delete/{id}', [BrandController::class, 'delete']) ->middleware('role:admin,manager')->name('brands.delete');
    // Unit
    Route::get('/units', [UnitController::class, 'index']) ->middleware('role:admin,manager')->name('units');
    Route::get('/units/create', [UnitController::class, 'create']) ->middleware('role:admin,manager')->name('units.create');
    Route::post('/units/store', [UnitController::class, 'store']) ->middleware('role:admin,manager')->name('units.store');
    Route::get('/units/edit/{id}', [UnitController::class, 'edit']) ->middleware('role:admin,manager')->name('units.edit');
    Route::post('/units/update/{id}', [UnitController::class, 'update']) ->middleware('role:admin,manager')->name('units.update');
    Route::get('/units/delete/{id}', [UnitController::class, 'delete']) ->middleware('role:admin,manager')->name('units.delete');
    // Products
    Route::get('/products', [ProductController::class, 'index']) ->middleware('role:admin,manager')->name('products');
    Route::get('/products/create', [ProductController::class, 'create']) ->middleware('role:admin,manager')->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store']) ->middleware('role:admin,manager')->name('products.store');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit']) ->middleware('role:admin,manager')->name('products.edit');
    Route::post('/products/update/{id}', [ProductController::class, 'update']) ->middleware('role:admin,manager')->name('products.update');
    Route::get('/products/delete/{id}', [ProductController::class, 'delete']) ->middleware('role:admin,manager')->name('products.delete');

});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
