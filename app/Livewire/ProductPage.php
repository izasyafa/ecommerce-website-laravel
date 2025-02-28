<?php

namespace App\Livewire;

use App\Models\Brands;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;

#[Title('Products - IzzaCode')]
class ProductPage extends Component
{
    #[Url]
    public $selected_categories = [];
    public function render()
    {
        $productResult = Product::query()->where('is_active',1);

        if(!empty($this->selected_categories)){
            $productResult->whereIn('id', $this->selected_categories);
        } else{

        }

        $categories = Category::where('is_active',1)->get();
        $brands = Brands::where('is_active',1)->get();
        $products = $productResult->paginate(6);

        return view('livewire.product-page', compact('categories', 'brands', 'products'));
    }
}
