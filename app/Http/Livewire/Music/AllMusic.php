<?php

namespace App\Http\Livewire\Music;

use App\Models\Music;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\Component;

class AllMusic extends Component
{

    use WithPagination;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';
    public $user = User::class;

    public function render()
    {
        return view('livewire.music.all-music')
        ->with('musics', auth()->user()->musics()->paginate(3))
        ->extends('layouts.app');
    }

    public function delete(Music $music)
    {
        //dd("Hello");
        //delete folder 
        $this->authorize('delete', $music);
        
        $deleted = Storage::disk('musics')->deleteDirectory(explode('/', $music->songSrc)[0]);
        if ($deleted) {
            $music->delete();
        }

        return back();
    }
}
