<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\CategoryController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\OrdersController;
use App\Http\Controllers\admin\DiscountController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\PagesController;


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

route::get('/home',[HomeController::class,'index'])->middleware('auth')->name('home');

//Category routes 

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/category/list', [CategoryController::class, 'CategoryList'])->name('category.list');
    Route::get('/category/create', [CategoryController::class, 'Create'])->name('category.create');
    Route::post('/category/store', [CategoryController::class, 'Store'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'Edit'])->name('category.edit');
    Route::put('/category/{id}/update', [CategoryController::class, 'Update'])->name('category.update');
    Route::get('/category/{id}/delete', [CategoryController::class, 'Delete'])->name('category.delete');
    //temp-images.create
    Route::post('/upload-temp-image', [TempImagesController::class, 'Create'])->name('temp-images.create');
    
    //subcategory
    Route::get('/subcategory/list', [SubCategoryController::class, 'Create'])->name('subcategory.list');
    // Route::get('/subcategory/create', [SubCategoryController::class, 'Create'])->name('subcategory.create');
    Route::post('/subcategory/store', [SubCategoryController::class, 'Store'])->name('subcategory.store');
    Route::get('/subcategory/{id}/edit', [SubCategoryController::class, 'Edit'])->name('subcategory.edit');
    Route::put('/subcategory/{id}/update', [SubCategoryController::class, 'Update'])->name('subcategory.update');
    Route::get('/subcategory/{id}/delete', [SubCategoryController::class, 'Delete'])->name('subcategory.delete');
    //Brands
    Route::get('/brands/list', [BrandsController::class, 'BrandsList'])->name('brands.list');

    //Products
    Route::get('/products/list', [ProductsController::class, 'ProductsList'])->name('products.list');

    //Shipping
    Route::get('/shipping/list', [ShippingController::class, 'ShippingList'])->name('shipping.list');

    //Orders
    Route::get('/orders/list', [OrdersController::class, 'OrdersList'])->name('orders.list');

    //Discount
    Route::get('/discount/list', [DiscountController::class, 'DiscountList'])->name('discount.list');

     //Users
    Route::get('/users/list', [UsersController::class, 'UserList'])->name('users.list');

     //pages
    Route::get('/pages/list', [PagesController::class, 'PageList'])->name('pages.list');
});

route::get('adminonly',[HomeController::class,'adminOnly'])->middleware(['auth','admin']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';