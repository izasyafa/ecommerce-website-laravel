<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{

  // Add item to Cart
  public static function addToCart($product_id)
  {
    $cart_items = self::getCartItemsFromCookie();
    $existing_key = array_search($product_id, array_column($cart_items, 'product_id'));

    if ($existing_key !== false) {
      $cart_items[$existing_key]['quantity']++;
      $cart_items[$existing_key]['total_amount'] = $cart_items[$existing_key]['quantity'] * $cart_items[$existing_key]['unit_amount'];
    } else {
      $product = Product::find($product_id, ['id', 'name', 'price', 'image']);

      if ($product) {
        $cart_items[] = [
          'product_id' => $product->id,
          'name' => $product->name,
          'quantity' => 1,
          'unit_amount' => $product->price,
          'total_amount' => $product->price,
          'image' => $product->image[0] ?? null
        ];
      }
    }

    self::addCartItemsToCookie($cart_items);
    return count($cart_items);
  }

  public static function addToCartWithQty($product_id, $qty = 1)
  {
    $cart_items = self::getCartItemsFromCookie();
    $existing_key = array_search($product_id, array_column($cart_items, 'product_id'));

    if ($existing_key !== false) {
      $cart_items[$existing_key]['quantity'] = $qty;
      $cart_items[$existing_key]['total_amount'] = $cart_items[$existing_key]['quantity'] * $cart_items[$existing_key]['unit_amount'];
    } else {
      $product = Product::find($product_id, ['id', 'name', 'price', 'image']);

      if ($product) {
        $cart_items[] = [
          'product_id' => $product->id,
          'name' => $product->name,
          'quantity' => $qty,
          'unit_amount' => $product->price,
          'total_amount' => $product->price,
          'image' => $product->image[0] ?? null
        ];
      }
    }

    self::addCartItemsToCookie($cart_items);
    return count($cart_items);
  }

  // Remove Items from cart
  static public function removeFromCart($product_id)
  {
    $cart_items = self::getCartItemsFromCookie();

    foreach ($cart_items as $key => $item) {
      if ($cart_items['product_id'] == $product_id) {
        unset($cart_items[$key]);
      }
    }

    self::addCartItemsToCookie($cart_items);
    return $cart_items;
  }

  // add Cart Items to Cookies
  static public function addCartItemsToCookie($cart_items)
  {
    $json_cart = json_encode($cart_items);

    Cookie::queue('cart_items', $json_cart, 60 * 24 * 30);
  }

  // Clear Cart Items from Cookie
  static public function clearCartItemsFromCookie()
  {
    Cookie::queue(Cookie::forget('cart_items'));
  }

  // get Cart Items from Cookies
  static public function getCartItemsFromCookie()
  {
    $cart_items = json_decode(Cookie::get('cart_items'), true);
    if (!is_array($cart_items)) {
      return $cart_items = [];
    }

    return $cart_items;
  }

  // Increment Cart Items Quantity
  static public function incrementCartItemQuantity($product_id)
  {
    $cart_items = self::getCartItemsFromCookie();

    foreach ($cart_items as $key => $item) {
      if ($cart_items['product_id'] == $product_id) {
        $cart_items[$key]['quantity']++;
        $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
      }
    }

    self::addCartItemsToCookie($cart_items);
    return $cart_items;
  }

  // Decrement Cart Items Quantity
  static public function decrementCartItemQuantity($product_id)
  {
    $cart_items = self::getCartItemsFromCookie();

    foreach ($cart_items as $key => $item) {
      if ($cart_items['product_id'] == $product_id) {
        if ($cart_items[$key]['quantity'] > 1) {
          $cart_items[$key]['quantity']--;
          $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
        } else {
          unset($cart_items[$key]);
        }
      }

      self::addCartItemsToCookie($cart_items);
      return $cart_items;
    }
  }

  // Get Cart Total Amount
  static public function getCartTotalAmount($items)
  {
    return array_sum(array_column($items, 'total_amount'));
  }
}
