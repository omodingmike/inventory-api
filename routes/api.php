<?php

    use App\Http\Controllers\ExpenseCategoryController;
    use App\Http\Controllers\inventory\CategoryController;
    use App\Http\Controllers\inventory\ContactController;
    use App\Http\Controllers\inventory\ExpenseController;
    use App\Http\Controllers\inventory\ProductController;
    use App\Http\Controllers\inventory\RevenueController;
    use App\Http\Controllers\inventory\SaleController;
    use App\Http\Controllers\inventory\SmileIDController;
    use App\Http\Controllers\inventory\SubCategoryController;
    use App\Http\Controllers\inventory\SupplierController;
    use App\Http\Controllers\inventory\UnitController;
    use Illuminate\Support\Facades\Route;


    Route ::resource( 'products' , ProductController::class );
    Route ::resource( 'expense-categories' , ExpenseCategoryController::class );
    Route ::resource( 'categories' , CategoryController::class );
    Route ::resource( 'sales' , SaleController::class );
    Route ::resource( 'revenues' , RevenueController::class );
    Route ::resource( 'expenses' , ExpenseController::class );
    Route ::resource( 'contacts' , ContactController::class );
    Route ::resource( 'suppliers' , SupplierController::class );
    Route ::resource( 'subcategories' , SubCategoryController::class );

    Route ::resource( 'units' , UnitController::class );

    Route ::post( 'contact-update' , [ ContactController::class , 'update' ] );

    Route ::get( 'filter-category-products' , [ ProductController::class , 'filterProducts' ] );
    Route ::post( 'update-product' , [ ProductController::class , 'update' ] );
    Route ::get( 'product-details' , [ ProductController::class , 'details' ] );

    Route ::get( 'category-products' , [ CategoryController::class , 'categoryProducts' ] );
    Route ::get( 'getProductCategories' , [ CategoryController::class , 'getCategoryProducts' ] );

    Route ::get( 'expenses-incomes' , [ ExpenseController::class , 'expensesAndIncomes' ] );
//    Route ::get( 'expense-categories' , [ ExpenseController::class , 'expenseCategories' ] );

    Route ::get( 'sale' , [ SaleController::class , 'show' ] );
    Route ::get( 'generate' , [ SmileIDController::class , 'generateSignature' ] );
    Route ::post( 'test' , [ SmileIDController::class , 'pathExists' ] );
    Route ::post( 'files' , [ SmileIDController::class , 'files' ] );
    Route ::post( 'submit' , [ SmileIDController::class , 'submitJob' ] );

    Route ::post( 'smile' , [ SmileIDController::class , 'callback' ] ) -> name( 'callback' );
    Route ::get( 'smile' , [ SmileIDController::class , 'generateSignature' ] );

    //    Route ::prefix( 'inv' ) -> namespace( 'inventory' ) -> group( function () {
    //        //  Products Routes
    //        Route ::get( 'filter-category-products' , 'ProductController@filterProducts ' );
    //        Route ::post( 'products' , 'ProductController@store' );
    //        Route ::post( 'update-product' , 'ProductController@update' );
    //
    //        // Categories Routes
    //        Route ::get( 'categories' , 'CategoryController@index ' );
    //        Route ::get( 'category-products' , 'CategoryController@index ' );
    //        Route ::get( 'category-products' , 'CategoryController@getCategoryProducts ' );
    //        Route ::post( 'categories' , 'CategoryController@store ' );
    //        Route ::post( 'categories' , 'CategoryController@update ' );
    //
    //        // Sale Routes
    //        Route ::get( 'sales' , 'SaleController@index ' );
    //        Route ::get( 'sale' , 'SaleController@show ' );
    //
    //        // Expense Routes
    //        Route ::get( 'expenses' , 'ExpenseController@index ' );
    //        Route ::get( 'expense-categories' , 'ExpenseController@expenseCategories ' );
    //        Route ::get( 'expenses-incomes' , 'ExpenseController@expensesAndIncomes ' );
    //
    //        // Revenue Routes
    //        Route ::get( 'revenues' , 'RevenueController@index ' );
    //
    //        // Contacts Routes
    //        Route ::get( 'contacts' , 'ContactController@index ' );
    //        Route ::post( 'contacts' , 'ContactController@store ' );
    //
    //        // Supplier Routes
    //        Route ::get( 'suppliers' , 'SupplierController@index ' );
    //        Route ::post( 'suppliers' , 'SupplierController@store ' );
    //
    //        // Subcategories Routes
    //        Route ::get( 'subcategories' , 'SubCategoryController@index ' );
    //        Route ::post( 'subcategories' , 'SubCategoryController@store ' );
    //
    //        // Units Routes
    //        Route ::get( 'units' , 'UnitController@index ' );
    //        Route ::post( 'units' , 'UnitController@store ' );
    //
    //        Route ::post( 'smile' , [ SmileIDController::class , 'callback' ] );
    //        Route ::get( 'smile' , [ SmileIDController::class , 'generateSignature' ] );
    //    } );


