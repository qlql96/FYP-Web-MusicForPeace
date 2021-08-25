<?php

namespace App\Http\Livewire\Music;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Music;
use App\Models\User;
use App\Models\Genre;
use App\Models\Album;
use App\Models\Theme;
use Livewire\Component;

class UploadMusic extends Component
{

    use WithFileUploads;
    public $user = User::class;
    public $music = Music::class;

    public $musicFile;
    public $songSrc;
    public $songPicSrc;
    public $path;

    public $genres;
    public $themes;
    public $albums;
    public $selectedGenre = NULL;
    public $selectedTheme = NULL;
    public $selectedAlbum = NULL;

    protected $rules = [
        'musicFile' => 'nullable|mimes:mp4|max:1228800',
        'songSrc' => 'required|mimes:mp3|max:1228800',
        'songPicSrc' => 'required|mimes:jpeg,bmp,png,gif,svg|max:1228800',
        'music.title' => 'required|max:255',
        'selectedGenre' => 'required',
        'selectedTheme' => 'required',
        'music.musicYear' => 'required|numeric',
        'music.musicLyrics' => 'nullable|required|max:3000',
        'music.musicLyricsWriter' => 'required|max:255',
        'music.musicInstrumentalMaker' => 'required|max:255',
        'music.musicProducer' => 'required|max:255',
        'music.musicPerformer' => 'required|max:255',
    ];
    public function mount(User $user){
        $this->genres = Genre::all();
        $this->themes = Theme::all();
        $this->albums = Album::all();
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.music.upload-music')->extends('layouts.app');
    }

    public function fileCompleted(){

        // validation
        $this->validate();
        //save the file
        //$path = $this->musicFile->store('music-tmp');
    }

    public function upload()
    {
         //$this->validate();
        $this->validate();
        //save the file
         //$path = $this->musicFile->storePublicly('music-tmp','public');
         //$pathSong = $this->songSrc->storePublicly('music-tmp','public');
         //$pathSongPic = $this->songPicSrc->storePublicly('music-tmp','public');
        $reference = "ABC";
        $name = "Test";
        
        $newFolder = ($this->user->email).time().rand();
        //Storage::makeDirectory($newFolder);
        if($this->musicFile !=null){
             $path = $this->musicFile->storePublicly('music/'.$newFolder,'public');
             $videoSrc = explode('/', $path)[1] . '/' .explode('/', $path)[2];
        }else{
            $videoSrc = null;
        }
        if($this->songSrc != null){
            $pathSong = $this->songSrc->storePublicly('music/'.$newFolder,'public');
            $songSrc = explode('/', $pathSong)[1] . '/' .explode('/', $pathSong)[2];
        }else{
            $songSrc = null;
        }
        if($this->songPicSrc != null){
            $pathSongPic = $this->songPicSrc->storePublicly('music/'.$newFolder,'public');
            $songPicSrc = explode('/', $pathSongPic)[1] . '/' .explode('/', $pathSongPic)[2];
        }else{
            $songPicSrc = null;
        }
        //$path = $this->musicFile->move(public_path('app/public/music-tmp/'.$reference), $name);
        //$pathSong = $this->songSrc->move(public_path('app/public/music-tmp/'.$reference), $name);
        //$pathSongPic = $this->songPicSrc->move(public_path('app/public/music-tmp/'.$reference), $name);
         
         //create video record in sb
         $this->user->musics()->create([
            'musicTitle' => $this->music['title'],
            'songSrc' => $songSrc,
            'songPicSrc' => $songPicSrc,
            //'videoSrc' => 'untitled',ß
            'videoSrc' => $videoSrc,
            'token' => uniqid(true),
            'albumId' => $this->selectedAlbum,
            'genreId' => $this->selectedGenre,
            'themeId' => $this->selectedTheme,
            'musicYear' => $this->music['musicYear'],
            'musicLyrics' => $this->music['musicLyrics'],
            'musicLyricsWriter' => $this->music['musicLyricsWriter'],
            'musicInstrumentalMaker' => $this->music['musicInstrumentalMaker'],
            'musicProducer' => $this->music['musicProducer'],
            'musicPerformer' => $this->music['musicPerformer'],
            'musicNoOfLikes' => 0,
            'musicNoOfPlays' => 0,
            'musicNoOfShares' => 0,
         ]);

             
        $url = 'http://127.0.0.1:5000/sentiment';
   
        $lyrics = $this->music['musicLyrics'];
        $lyrics = str_replace("\n", '', $lyrics);
        if (!preg_match('/[^A-Za-z0-9 #$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]+/', $lyrics)){
            $array = array (
                "lyrics" => $this->music['musicLyrics']
            );

            $data_string = json_encode($array);
            #var_dump ($data_string);
            $url = 'http://127.0.0.1:5000/sentiment';

            //Create cURL connection
            $curl = curl_init($url);

            //set cURL options
            #curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POST,1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($array));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded',
            ));
            //'Content-Length: ' . strlen($data_string)),
           
            $curl_response = curl_exec($curl);
            if($curl_response){
                $curl_response = json_decode( $curl_response );
                $lyricSentiment = new LyricSentiment;
                $lyricSentiment->songId = $this->music['id'];
                $lyricSentiment->anger = $curl_response->anger;
                $lyricSentiment->disgust = $curl_response->disgust;
                $lyricSentiment->fear = $curl_response->fear;
                $lyricSentiment->joy = $curl_response->joy;
                $lyricSentiment->sadness = $curl_response->sadness;
                $lyricSentiment->trust = $curl_response->trust;
                $lyricSentiment->anticipation = $curl_response->anticipation;
                $lyricSentiment->surprise = $curl_response->surprise;
                $lyricSentiment->positive = $curl_response->positive;
                $lyricSentiment->negative = $curl_response->negative;
                $lyricSentiment->save();
            }
            //dd($curl_response);

        }else{
            dd("Lyrics not in English");
        }
         //redirect to edit route
         session()->flash('message', 'Music Uploaded');
    }


}
