<?php

namespace App\Http\Livewire\Music;

use Livewire\Component;

class ViewMusic extends Component
{
    public function render()
    {
        return view('livewire.music.view-music')->extends('layouts.app');
    }
}
