<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\PromotionNotificationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NotificationController;
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
Route::post('/chat', [App\Http\Controllers\ChatController::class, 'chat']);
Route::get('/chat', function () {
    return view('chat');
}); 
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit'); 

// Authentication routes
Route::middleware('guest')->group(function () {
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/items/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    //  Commented out until controllers are created
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    
    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist/check/{product}', [WishlistController::class, 'checkStatus'])->name('wishlist.check');
    
    // Payment routes
    Route::get('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/callback/{method}', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::post('/payment/ipn/{method}', [PaymentController::class, 'handleIPN'])->name('payment.ipn');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    
    
});

// Products routes
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/details', [App\Http\Controllers\ProductController::class, 'getDetails'])->name('products.details');
Route::get('/products/{product}/quick-view', [App\Http\Controllers\ProductController::class, 'quickView'])->name('products.quick-view');

// Profile routes
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
});

Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

// Search routes
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public routes
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    });

    // Protected routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
        //user routes
        Route::resource('users', UserController::class)->except(['show']);
        Route::put('users/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/send-reset', [UserController::class, 'sendPasswordReset'])->name('users.send-reset');
        //Product routes
        Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
        Route::put('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::resource('products', ProductController::class);
         // Category routes
        Route::get('categories/trashed', [CategoryAdminController::class, 'trashed'])->name('categories.trashed');
        Route::post('categories/{id}/restore', [CategoryAdminController::class, 'restore'])->name('categories.restore');
        Route::delete('categories/{id}/force-delete', [CategoryAdminController::class, 'forceDelete'])->name('categories.force-delete');
        Route::resource('categories', CategoryAdminController::class);
        
        // Order management routes
        Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/update-status', [OrderAdminController::class, 'updateStatus'])->name('orders.update-status');
        //Promotion routes
        Route::resource('promotions', PromotionController::class);
        Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
        Route::get('/promotions/form', [PromotionController::class, 'form'])->name('promotions.form');
        Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
        
        Route::get('/promotions/{promotion}', [PromotionController::class, 'show'])->name('promotions.show');
        Route::put('/promotions/{promotion}', [PromotionController::class, 'update'])->name('promotions.update');
        Route::delete('/promotions/{promotion}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
    });

    
    // Route::get('/blog', [BlogController::class, 'index'])->name('blog');
    // Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    

});

Route::get('/check-promotions', [NotificationController::class, 'checkPromotions'])->name('check.promotions');
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

// Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/check-promotions', [App\Http\Controllers\NotificationController::class, 'checkPromotions']);
    Route::post('/notifications/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
});


