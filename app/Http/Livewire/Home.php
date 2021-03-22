<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Animal;

class Home extends Component
{
    public $animals;

    public function render()
    {
        $this->animals = Animal::all();
        return view('pages.home')
        ->layout('layouts.home');
    }
}
