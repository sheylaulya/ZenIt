<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HControllerr;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PController;
use App\Http\Controllers\SController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopProfile;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SellerdashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerEditController;
use App\Http\Controllers\SellerProductController;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Route::get('/', [HControllerr::class, 'index'])->name('HomePage');


    Route::get('/product/detail/{product:slug?}', [PController::class, 'show'])->name('product.show');
    Route::get('/product/{slug?}',[SController::class,'index'])->name('product.cate.filter');
    Route::get('/product/tag/{slug?}', [SController::class, 'tag'])->name('product.tag.filter');

    Route::get('/search/{slug?}', [SController::class, 'search'])->name('product.search');
    
    Route::get('/detail', function () {
        return view('detail');
    })->name('detail');


    //CART
    Route::group(['middleware' => 'auth',  'prefix' => 'cart',  'as' => 'cart.'],function(){
        Route::get('/', [CartController::class, 'CartPage'])->name('cart');
        Route::get('/{product:id?}', [CartController::class, 'store'])->name('cart.store');
        Route::get('/modal/{product:id?}', [CartController::class, 'storemodal']);
        Route::get('/dec/{cart:id?}', [CartController::class, 'dec'])->name('cart.dec');
        Route::get('/inc/{cart:id?}', [CartController::class, 'inc'])->name('cart.inc');
        Route::get('/dest/{cart:id?}', [CartController::class, 'destroy'])->name('cart.dest');
    });

    Route::group(['middleware' => 'auth',  'prefix' => 'favorite',  'as' => 'favorite.'],function(){
        Route::get('/', [FavoriteController::class, 'show'])->name('fav');
        Route::get('/add/{product:id?}', [FavoriteController::class, 'add'])->name('favorite.add');
        Route::get('/del/{id}', [FavoriteController::class, 'destroy']);
    });

    Route::get('/coba/coba', [TransactionController::class, 'store']);



    route::get('/shop/profile/{slug?}', [ShopProfile::class, 'show'])->name('shop.show.profile');
    
    Route::get('/setting', function () {
        return view('dashboard');
    });

Auth::routes();

//PROFILES AND CHECKOUT
Route::group(['middleware' => 'auth'], function(){
    Route::get('/profile/create', [UserProfileController::class, 'create']);
    Route::post('/profile', [UserProfileController::class, 'store']);
    Route::put('/profile', [UserProfileController::class, 'update'])->name('update.profile');
    
    Route::get('/profile/your-profile', [UserProfileController::class, 'show'])->name('profile.cust');
    Route::get('/profile/edityour', [UserProfileController::class, 'edit'])->name('editprofile.cust');
    
    Route::get('checkoutdetail', [OrderController::class, 'index'])->name('checkout-detail');
    Route::get('checkoutdetail/payment', [OrderController::class, 'payment'])->name('checkout-payment');
    Route::post('place/order', [OrderController::class, 'storeOrder'])->name('place-order');
    Route::get('checkoutdetail/payment/success', [OrderController::class, 'complete'])->name('checkout-complete');
});


// Route::get('/seller', function () {
//     return view('seller.loginseller');
// })->name('loginseller');

// Route::get('/sellerreg', function () {
//     return view('seller.regisseller');
// })->name('regisseller');

Route::group(['middleware' => ['auth','CheckLevel:admin,seller'],  'prefix' => 'seller',  'as' => 'seller.'],function(){
    Route::resource('shop-profile', ShopController::class);

    Route::get('/profile/edit', function () {
        return view('profile.profile-edit');
    })->name('edit.profile');
    
    Route::put('/profile/save/{id}', [SellerEditController::class, 'update']);
    Route::patch('/profile/{id}', [SellerEditController::class, 'updateIMG']);
    
    Route::get('/profile', function(){
        return view('profile.profile');
    })->name('profile');

    Route::get('/products',[SellerProductController::class, 'show'])->name('products');
    Route::post('/product/change/{id}',[SellerProductController::class, 'changeStatus'])->name('products.changeStatus');
    Route::get('/product/add', [SellerProductController::class, 'createProduct'])->name('product.add');
    Route::post('/product/add', [SellerProductController::class, 'addProduct'])->name('product.store');
    Route::get('/product/edit/{id?}', [SellerProductController::class, 'editProduct'])->name('product.edit');
    Route::put('/product/{id?}', [SellerProductController::class, 'updateProduct'])->name('product.update');

    Route::get('/setting-scedhule', function () {
        return view('seller.setting-scedhule');
    })->name('setting-scedhule');
});

//Store-IMG
Route::post('profile/image',[UserProfileController::class, 'storeImage']);
Route::post('shop-profile/image',[ShopController::class, 'storeImage']);
Route::post('product/image',[SellerProductController::class, 'storeImage']);

// //old profile seller
// Route::get('/profile-seller-old', function () {
//     return view('seller.profile-old');
// })->name('profileseller');
// Route::get('/profile-edit-old', function () {
//     return view('seller.profile-edit-old');
// })->name('profile-edit1');


// Route::get('/productseller', function () {
//     return view('seller.product-seller');
// })->name('product-seller');


Route::get('/upcoming', function () {
    return view('upcoming');
})->name('upcoming');

Route::get('/upcomingS', function () {
    return view('seller.upcoming');
})->name('upcomingS');

Route::get('/processed', function () {
    return view('seller.processed');
})->name('processed');

Route::get('/completed', function () {
    return view('seller.complete-order');
})->name('completed');

Route::get('/canceled', function () {
    return view('seller.canceled-order');
})->name('canceled');

Route::get('/report', function () {
    return view('seller.report');
})->name('report');

Route::get('/monthlyreport', function () {
    return view('seller.monthly-report');
})->name('monthly-report');

// Route::get('/addproduct', function () {
//     return view('seller.add-product');
// })->name('add-product');


Route::get('add-rating', [RatingController::class, 'add']);

Route::get('/setting', function () {
    return view('setting-cust');
})->name('settingCust');

Route::get('/setting-info', function () {
    return view('seller.setting-info');
})->name('setting-info');


Route::get('/report-invoice', function () {
    return view('seller.report-invoice');
})->name('report-invoice');

Route::put('/user/edit/{id}', [UserProfileController::class, 'update']);

Route::get('/product/detail/review/{product:slug?}', [ReviewController::class, 'show'])->name('product.review');

Route::get('/seller/editstore', function () {
    return view('seller.setting-store');
})->name('edit-info');

Route::get('/notif', function () {
    return view('notif');
})->name('notif');

Route::get('/otpverification', function () {
    return view('otp-verif');
})->name('otp');

Route::get('/customer/order', function () {
    return view('myorder');
})->name('order');


Route::get('export/sale/data', [PController::class, 'export']);

Route::get('/setup/profile', function () {
    return view('setup');
})->name('setup');
