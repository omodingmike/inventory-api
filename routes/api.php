<?php

    use App\Http\Controllers\inventory\CategoryController;
    use App\Http\Controllers\inventory\ContactController;
    use App\Http\Controllers\inventory\ExpenseController;
    use App\Http\Controllers\inventory\ProductController;
    use App\Http\Controllers\inventory\RevenueController;
    use App\Http\Controllers\inventory\SaleController;
    use App\Http\Controllers\inventory\SubCategoryController;
    use App\Http\Controllers\inventory\SupplierController;
    use App\Http\Controllers\inventory\UnitController;
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

    Route ::resource( 'products' , ProductController::class );
    Route ::resource( 'categories' , CategoryController::class );
    Route ::resource( 'sales' , SaleController::class );
    Route ::resource( 'revenues' , RevenueController::class );
    Route ::resource( 'expenses' , ExpenseController::class );
    Route ::resource( 'contacts' , ContactController::class );
    Route ::resource( 'suppliers' , SupplierController::class );
    Route ::resource( 'subcategories' , SubCategoryController::class );
    Route ::resource( 'units' , UnitController::class );
    Route ::get( 'filter-category-products' , [ ProductController::class , 'filterProducts' ] );                               //category-products?category_id=1&from=01-07-2023&to=01-07-2023
    Route ::get( 'sale' , [ SaleController::class , 'show' ] );                                                                //category-products?category_id=1&from=01-07-2023&to=01-07-2023
    Route ::post( 'update-product' , [ ProductController::class , 'update' ] );                                                //category-products?category_id=1&from=01-07-2023&to=01-07-2023
    Route ::get( 'expenses-incomes' , [ ExpenseController::class , 'expensesAndIncomes' ] );                                   //category-products?category_id=1&from=01-07-2023&to=01-07-2023
    Route ::get( 'getProductCategories' , [ CategoryController::class , 'getCategoryProducts' ] );                        //
    //category-products?category_id=1&from=01-07-2023&to=01-07-2023

