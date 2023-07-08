<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('sales', SaleController::class);
Route::resource('revenues', RevenueController::class);
Route::resource('expenses', ExpenseController::class);
Route::get('filter-category-products', [CategoryController::class, 'filterCategories']);//category-products?category_id=1&from=01-07-2023&to=01-07-2023
Route::get('category-products', [ProductController::class, 'filterProducts']);          //category-products?category_id=1&from=01-07-2023&to=01-07-2023
Route::resource('suppliers', SupplierController::class);
Route::resource('subcategories', SubCategoryController::class);
Route::resource('units', UnitController::class);
