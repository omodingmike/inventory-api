<?php

    use App\Http\Controllers\Auth\AuthController;
    use App\Http\Controllers\inventory\CategoryController;
    use App\Http\Controllers\inventory\ContactController;
    use App\Http\Controllers\inventory\ExpenseCategoryController;
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
    Route ::post( 'search-product' , [ ProductController::class , 'search' ] );

    Route ::get( 'filter-category-products' , [ ProductController::class , 'filterProducts' ] );
    Route ::post( 'update-product' , [ ProductController::class , 'update' ] );
    Route ::get( 'product-details' , [ ProductController::class , 'details' ] );

    Route ::get( 'all-categories' , [ CategoryController::class , 'all' ] );
    Route ::get( 'all-subcategories' , [ SubCategoryController::class , 'all' ] );
    Route ::get( 'all-contacts' , [ ContactController::class , 'all' ] );

    Route ::get( 'expenses-incomes' , [ ExpenseController::class , 'expensesAndIncomes' ] );
    Route ::get( 'daily-expenses' , [ ExpenseController::class , 'daily' ] );
    Route ::get( 'weekly-expenses' , [ ExpenseController::class , 'weekly' ] );
    Route ::get( 'yearly-expenses' , [ ExpenseController::class , 'yearly' ] );
    Route ::get( 'monthly-expenses-top-section' , [ ExpenseController::class , 'topSection' ] );
    Route ::get( 'monthly-expenses-bottom-section' , [ ExpenseController::class , 'bottomSection' ] );

    Route ::get( 'sale' , [ SaleController::class , 'show' ] );
    Route ::get( 'generate' , [ SmileIDController::class , 'generateSignature' ] );
    Route ::post( 'test' , [ SmileIDController::class , 'pathExists' ] );
    Route ::post( 'files' , [ SmileIDController::class , 'files' ] );
    Route ::post( 'submit' , [ SmileIDController::class , 'submitJob' ] );

    Route ::post( 'smile' , [ SmileIDController::class , 'callback' ] ) -> name( 'callback' );
    Route ::post( 'upload' , [ SmileIDController::class , 'testUpload' ] ) -> name( 'testUpload' );
    Route ::get( 'check' , [ SmileIDController::class , 'checkUploadedFile' ] ) -> name( 'check-uploaded-file' );
    Route ::get( 'smile' , [ SmileIDController::class , 'generateSignature' ] );

    Route ::prefix( 'auth' ) -> group( function () {
        Route ::post( 'login' , [ AuthController::class , 'login' ] ) -> name( 'login' );
        Route ::post( 'register' , [ AuthController::class , 'register' ] ) -> name( 'register' );
    } );

    


