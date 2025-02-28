<?php

namespace App\Livewire;

use App\Models\Brands;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;

#[Title('Home - IzzaCode')]
class HomePage extends Component
{
    public function render()
    {
        $categories = Category::where('is_active', operator: 1)->get();
        $brands = Brands::where('is_active', operator: 1)->get();

        return view('livewire.home-page', compact('categories', 'brands'));
    }
}
