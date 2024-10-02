<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDropdown extends Component
{
    public $search;
    // public $searchResults = [];

    public $searchQuery = '';
    public $searchResults = [];

    public function updatedSearchQuery()
    {
        dd('hello');
        $this->searchResults = $this->search($this->searchQuery);
    }

    public function updatedSearch($newValue)
    {
        if (strlen($this->search) < 3) {
            $this->searchResults = [];
            return;
        }
        $response = Http::get('https://itunes.apple.com/search/?term=' . $this->search . '&limit=10');
        $this->searchResults = $response->json()['results'];
    }

    public function render()
    {
        return view('livewire.search-dropdown');
    }
}
