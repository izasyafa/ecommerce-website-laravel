<?php

namespace App\Livewire;

use App\Models\Brands;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Title('Products - IzzaCode')]
class ProductPage extends Component
{
    use LivewireAlert;
    #[Url]
    public $selected_categories = [];
    #[Url]
    public $selected_brands = [];
    #[Url]
    public $featured;
    #[Url]
    public $on_sale;

    public $minPrice;
    public $maxPrice;
    public $sort = 'latest';

    public function addToCart($product_id)
    {
        $total_count = CartManagement::addToCart($product_id);

        $this->dispatch('update-cart-count', total_count : $total_count)->to(Navbar::class);

        $this->alert('success', 'Product added to cart successfully',[
            'position' =>  'bottom-end',
            'timer' =>  3000,
            'toast' =>  true,
        ]);
    }

    public function render()
    {
        $productResult = Product::query()->where('is_active',1);

        if(!empty($this->selected_brands)){
            $productResult->whereIn('brand_id', $this->selected_brands);
        } else{

        }
        if(!empty($this->selected_categories)){
            $productResult->whereIn('id', $this->selected_categories);
        }

        if($this->featured){
            $productResult->where('is_featured',1);
        }

        if($this->on_sale){
            $productResult->where('on_sale', 1);
        }

        if(!empty($this->minPrice) && empty($this->maxPrice)){
            $productResult->where('price', '>=', $this->minPrice)->get();
        }

        if(!empty($this->maxPrice) && empty($this->minPrice)){
            $productResult->where('price', '<=', $this->maxPrice)->get();
        }

        if(!empty($this->minPrice) && !empty($this->maxPrice)){
            $productResult->whereBetween('price', [$this->minPrice, $this->maxPrice])->get();
        }

        if($this->sort == 'latest'){
            $productResult->latest()->get();
        }

        if($this->sort == 'price'){
            $productResult->orderBy('price','desc')->get();
        }

        $categories = Category::where('is_active',1)->get();
        $brands = Brands::where('is_active',1)->get();
        $products = $productResult->paginate(6);

        return view('livewire.product-page', compact('categories', 'brands', 'products'));
    }
}
