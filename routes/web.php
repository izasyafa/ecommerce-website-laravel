<?php

use App\Livewire\CartPage;
use App\Livewire\HomePage;
use App\Livewire\ProductPage;
use App\Livewire\CategoryPage;
use App\Livewire\ProductDetailPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('/categories', CategoryPage::class);
Route::get('/products', ProductPage::class);
Route::get('/cart', CartPage::class);
Route::get('/products/{slug}', ProductDetailPage::class);

