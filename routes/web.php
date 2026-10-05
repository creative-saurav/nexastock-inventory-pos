<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;

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
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'delete']) ->middleware('role:admin,manager')->name('categories.delete');
    // Brand
    Route::get('/brands', [BrandController::class, 'index']) ->middleware('role:admin,manager')->name('brands');
    Route::get('/brands/create', [BrandController::class, 'create']) ->middleware('role:admin,manager')->name('brands.create');
    Route::post('/brands/store', [BrandController::class, 'store']) ->middleware('role:admin,manager')->name('brands.store');
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit']) ->middleware('role:admin,manager')->name('brands.edit');
    Route::post('/brands/update/{id}', [BrandController::class, 'update']) ->middleware('role:admin,manager')->name('brands.update');
    Route::delete('/brands/delete/{id}', [BrandController::class, 'delete']) ->middleware('role:admin,manager')->name('brands.delete');
    // Unit
    Route::get('/units', [UnitController::class, 'index']) ->middleware('role:admin,manager')->name('units');
    Route::get('/units/create', [UnitController::class, 'create']) ->middleware('role:admin,manager')->name('units.create');
    Route::post('/units/store', [UnitController::class, 'store']) ->middleware('role:admin,manager')->name('units.store');
    Route::get('/units/edit/{id}', [UnitController::class, 'edit']) ->middleware('role:admin,manager')->name('units.edit');
    Route::post('/units/update/{id}', [UnitController::class, 'update']) ->middleware('role:admin,manager')->name('units.update');
    Route::delete('/units/delete/{id}', [UnitController::class, 'delete']) ->middleware('role:admin,manager')->name('units.delete');
    // Products
    Route::get('/products', [ProductController::class, 'index']) ->middleware('role:admin,manager')->name('products');
    Route::get('/products/create', [ProductController::class, 'create']) ->middleware('role:admin,manager')->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store']) ->middleware('role:admin,manager')->name('products.store');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit']) ->middleware('role:admin,manager')->name('products.edit');
    Route::post('/products/update/{id}', [ProductController::class, 'update']) ->middleware('role:admin,manager')->name('products.update');
    Route::delete('/products/delete/{id}', [ProductController::class, 'delete']) ->middleware('role:admin,manager')->name('products.delete');
    // Supplier
    Route::get('/suppliers', [SupplierController::class, 'index']) ->middleware('role:admin,manager')->name('suppliers');
    Route::get('/suppliers/create', [SupplierController::class, 'create']) ->middleware('role:admin,manager')->name('suppliers.create');
    Route::post('/suppliers/store', [SupplierController::class, 'store']) ->middleware('role:admin,manager')->name('suppliers.store');
    Route::get('/suppliers/edit/{id}', [SupplierController::class, 'edit']) ->middleware('role:admin,manager')->name('suppliers.edit');
    Route::post('/suppliers/update/{id}', [SupplierController::class, 'update']) ->middleware('role:admin,manager')->name('suppliers.update');
    Route::delete('/suppliers/delete/{id}', [SupplierController::class, 'delete']) ->middleware('role:admin,manager')->name('suppliers.delete');

    // Purchase
    Route::get('/purchases', [PurchaseController::class, 'index']) ->middleware('role:admin,manager')->name('purchases');
    Route::get('/purchases/create', [PurchaseController::class, 'create']) ->middleware('role:admin,manager')->name('purchases.create');
    Route::post('/purchases/store', [PurchaseController::class, 'store']) ->middleware('role:admin,manager')->name('purchases.store');
    Route::get('/purchases/edit/{id}', [PurchaseController::class, 'edit']) ->middleware('role:admin,manager')->name('purchases.edit');
    Route::post('/purchases/update/{id}', [PurchaseController::class, 'update']) ->middleware('role:admin,manager')->name('purchases.update');
    Route::delete('/purchases/delete/{id}', [PurchaseController::class, 'delete']) ->middleware('role:admin,manager')->name('purchases.delete');
    Route::get('/purchases/show/{id}', [PurchaseController::class, 'show'])
    ->middleware('role:admin,manager')
    ->name('purchases.show');

});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
