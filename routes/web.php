<?php

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

Route::get('/','HomeController@homepage');
Route::get('/product-details/{product}','ProductController@productDetails')->name('product.details');
Route::get('/shop/all-product','ProductController@shop')->name('shop');
Route::get('/category/all-product/{category}','ProductController@categorywiseProduct')->name('category.product');
Route::get('/brand/all-product/{brand}','BrandController@brandwiseProduct')->name('brand.product');
Route::get('/search/search-product','HomeController@searchResult')->name('search.result');

Auth::routes([
    'verify' => true
]);

Route::get('/register?type=seller', 'Auth\RegisterController@showRegistrationForm')->name('register.seller');

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/not-approved/seller', 'HomeController@notApprovedSeller')->name('notApprovedSeller');

    Route::middleware(['ApprovedSeller'])->group(function () {
        Route::get('/seller/dashboard', 'HomeController@sellerIndex')->name('seller.home');
        Route::get('/seller/request-new-shop', 'SellerController@shopRequest')->name('shop.request');
        Route::post('/seller/shop-management/add-shop', 'ShopController@addShop')->name('shop.add');
        Route::get('/seller/shop-management/edit-shop/{shop}', 'ShopController@editShop')->name('shop.edit');
        Route::patch('/seller/shop-management/update-shop/{shop}', 'ShopController@updateShop')->name('shop.update');
        Route::delete('/seller/shop-management/delete-shop/{shop}', 'ShopController@deleteShop')->name('seller.shop.delete');

        Route::get('/all-category', 'CategoryController@all')->name('category.all');
        Route::post('/add/category', 'CategoryController@addCategory')->name('category.add');
        Route::get('/all/category/edit/{category}', 'CategoryController@editCategory')->name('category.edit');
        Route::patch('/all/category/update/{category}', 'CategoryController@updateCategory')->name('category.update');
        Route::delete('/all/category/delete/{category}', 'CategoryController@deleteCategory')->name('category.delete');

        Route::get('/all/products', 'ProductController@all')->name('product.all');
        Route::get('/add/shop/product', 'ProductController@addProduct')->name('product.add');
        Route::post('/add/shop/product', 'ProductController@storeProduct')->name('product.store');
        Route::get('/shop/product/edit/{product}', 'ProductController@editProduct')->name('product.edit');
        Route::patch('/shop/product/update/{product}', 'ProductController@updateProduct')->name('product.update');
        Route::delete('/shop/product/delete/{product}', 'ProductController@deleteProduct')->name('product.delete');
        Route::delete('/shop/product/image/remove/{productImage}', 'ProductController@removeImageProduct')->name('productImage.delete');
  
        Route::get('seller/add-brand','BrandController@addBrand')->name('add.band');
        Route::post('seller/store-brand/','BrandController@StoreBand')->name('brand.store');
        Route::delete('brand/delete/{brand}','BrandController@delete')->name('brand.delete');
        Route::get('seller/seller-profile/{user}','SellerController@sellerupdate')->name('seller.profile');

    });

    Route::middleware(['Admin'])->group(function () {
        Route::get('/admin/dashboard', 'HomeController@index')->name('admin.home');
        Route::get('/admin/seller-management/requests','SellerController@viewRequests')->name('seller.requests');
        Route::get('/admin/seller-management/approved','SellerController@approvedSellers')->name('seller.approvedSellers');

        Route::post('/admin/seller-management/approve/{seller}','SellerController@approve')->name('seller.approve');
        Route::post('/adminseller-management/delete/{seller}','SellerController@delete')->name('seller.delete');


        Route::get('/admin/shop-management/requests','ShopController@viewRequests')->name('shop.requests');
        Route::get('/admin/shop-management/approved','ShopController@approvedShops')->name('shop.approvedShops');

        Route::post('/admin/shop-management/approve/{shop}','ShopController@approve')->name('shop.approve');
        Route::delete('/admin/shop-management/delete/{shop}','ShopController@delete')->name('shop.delete');

        Route::get('admin/add-slider','SliderController@addSlider')->name('add.slider');
        Route::post('admin/store-slider','SliderController@storeSlider')->name('slider.store');
        Route::delete('admin/delete-slider/{slider}','SliderController@delete')->name('slider.delete');
    });

    Route::middleware(['User'])->group(function () {
        Route::get('/home', 'HomeController@userIndex')->name('home');
    });

});
