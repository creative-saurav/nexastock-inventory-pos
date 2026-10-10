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
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerPanelController;
use App\Http\Controllers\StaffPanelController;
use App\Http\Controllers\FrontendController;

// Public website
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/shop', [FrontendController::class, 'products'])->name('shop.products');
Route::get('/shop/{slug}', [FrontendController::class, 'product'])->name('shop.products.show');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->middleware('role:admin')->name('admin.dashboard');


    Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->middleware('role:manager')->name('manager.dashboard');


    Route::get('/cashier/dashboard', [DashboardController::class, 'cashier'])->middleware('role:cashier')->name('cashier.dashboard');


    Route::get('/staff/dashboard', [StaffPanelController::class, 'dashboard'])->middleware('role:staff')->name('staff.dashboard');
    Route::get('/staff/stock', [StaffPanelController::class, 'stock'])->middleware('role:staff')->name('staff.stock');
    Route::get('/staff/purchases', [StaffPanelController::class, 'purchases'])->middleware('role:staff')->name('staff.purchases');
    Route::get('/staff/purchases/{id}', [StaffPanelController::class, 'purchaseShow'])->middleware('role:staff')->name('staff.purchases.show');
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
    Route::get('/purchases/show/{id}', [PurchaseController::class, 'show'])->middleware('role:admin,manager')->name('purchases.show');

    Route::get('/expense-categories', [ExpenseCategoryController::class, 'index']) ->middleware('role:admin,manager')->name('expense_categories');
    Route::get('/expense-categories/create', [ExpenseCategoryController::class, 'create']) ->middleware('role:admin,manager')->name('expense_categories.create');
    Route::post('/expense-categories/store', [ExpenseCategoryController::class, 'store']) ->middleware('role:admin,manager')->name('expense_categories.store');
    Route::get('/expense-categories/edit/{id}', [ExpenseCategoryController::class, 'edit']) ->middleware('role:admin,manager')->name('expense_categories.edit');
    Route::post('/expense-categories/update/{id}', [ExpenseCategoryController::class, 'update']) ->middleware('role:admin,manager')->name('expense_categories.update');
    Route::delete('/expense-categories/delete/{id}', [ExpenseCategoryController::class, 'delete']) ->middleware('role:admin,manager')->name('expense_categories.delete');

    Route::get('/expenses', [ExpenseController::class, 'index']) ->middleware('role:admin,manager')->name('expenses');
    Route::get('/expenses/create', [ExpenseController::class, 'create']) ->middleware('role:admin,manager')->name('expenses.create');
    Route::post('/expenses/store', [ExpenseController::class, 'store']) ->middleware('role:admin,manager')->name('expenses.store');
    Route::get('/expenses/edit/{id}', [ExpenseController::class, 'edit']) ->middleware('role:admin,manager')->name('expenses.edit');
    Route::post('/expenses/update/{id}', [ExpenseController::class, 'update']) ->middleware('role:admin,manager')->name('expenses.update');
    Route::delete('/expenses/delete/{id}', [ExpenseController::class, 'delete']) ->middleware('role:admin,manager')->name('expenses.delete');

    Route::prefix('reports')->middleware('role:admin,manager')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports');
        Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
        Route::get('/products', [ReportController::class, 'products'])->name('reports.products');
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
        Route::get('/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
        Route::get('/stock', [ReportController::class, 'stock'])->name('reports.stock');
    });

});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});

//POS Route
Route::middleware(['auth', 'role:admin,manager,cashier'])->group(function () {

    Route::get('/pos', [SaleController::class, 'create'])->name('pos');
    Route::post('/pos/store', [SaleController::class, 'store'])->name('pos.store');
    Route::post('/pos/customers', [SaleController::class, 'storeCustomer'])->name('pos.customers.store');
    Route::get('/pos/products/search', [SaleController::class, 'searchProducts'])
    ->name('pos.products.search');
    Route::get('/sales', [SaleController::class, 'index'])
        ->name('sales');
    Route::get('/sales/{id}', [SaleController::class, 'show'])
    ->name('sales.show');
    Route::get('/sales/{id}/invoice', [SaleController::class, 'invoice'])
    ->name('sales.invoice');

});




//Customer Panel
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    Route::get('/dashboard', [CustomerPanelController::class, 'dashboard'])->name('dashboard');
    Route::get('/purchases', [CustomerPanelController::class, 'purchases'])->name('purchases');
    Route::get('/purchases/{id}', [CustomerPanelController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{id}/invoice', [CustomerPanelController::class, 'invoice'])->name('purchases.invoice');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
