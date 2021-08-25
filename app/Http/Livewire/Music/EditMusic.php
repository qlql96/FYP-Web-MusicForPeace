<?php

namespace App\Http\Livewire\Music;


use App\Models\User;
use App\Models\Music;
use App\Models\Genre;
use App\Models\Album;
use App\Models\Theme;
use Livewire\Component;
use App\Models\LyricSentiment;
use Illuminate\Support\Facades\DB;

class EditMusic extends Component
{
    //public User $user;
    //public Music $music;
    public $user;
    public $music;

    public $genres;
    public $themes;
    public $albums;
    public $selectedGenre = NULL;
    public $selectedTheme = NULL;
    public $selectedAlbum = NULL;
    protected $rules = [

        'music.musicTitle' => 'required|max:255',
        'music.genreId' => 'required',
        'music.themeId' => 'required',
        'music.musicYear' => 'required|max:4',
        'music.musicLyrics' => 'required|max:3000',
        'music.musicLyricsWriter' => 'required|max:255',
        'music.musicInstrumentalMaker' => 'required|max:255',
        'music.musicProducer' => 'required|max:255',
        'music.musicPerformer' => 'required|max:255',

    ];
    public function mount($user, $music)
    {
        $this->genres = Genre::all();
        $this->themes = Theme::all();
        $this->albums = Album::all();
        
        $this->user = $user;
        $this->music = $music;
        $this->selectedGenre = $music->genreId;
        $this->selectedTheme = $music->themeId;
        $this->selectedAlbum = $music->albumId;
    }

    public function render()
    {
        return view('livewire.music.edit-music')
        ->extends('layouts.app');
    }

    public function update()
    {
        $this->validate();
        $this->music->update([
            'musicTitle' => $this->music['musicTitle'],
            'albumId' => $this->selectedAlbum,
            'genreId' => $this->selectedGenre,
            'themeId' => $this->selectedTheme,
            'musicYear' => $this->music['musicYear'],
            'musicLyrics' => $this->music['musicLyrics'],
            'musicLyricsWriter' => $this->music['musicLyricsWriter'],
            'musicInstrumentalMaker' => $this->music['musicInstrumentalMaker'],
            'musicProducer' => $this->music['musicProducer'],
            'musicPerformer' => $this->music['musicPerformer'],
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
                if(!DB::table('lyric_sentiments')->where('songId',$this->music['id'])->exists()){
               
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
                }else{
                    $id = DB::table('lyric_sentiments')->where('songId',$this->music['id'])->pluck('id');
                    #public LyricSentiment $lyricSentiment;
                    $lyricSentiment = LyricSentiment::where('id', $id)->first();
                    $lyricSentiment->update([
                        'anger' => $curl_response->anger,
                        'disgust' => $curl_response->disgust,
                        'fear' => $curl_response->fear,
                        'joy' => $curl_response->joy,
                        'sadness' => $curl_response->sadness,
                        'trust' => $curl_response->trust,
                        'anticipation' => $curl_response->anticipation,
                        'surprise' => $curl_response->surprise,
                        'positive' => $curl_response->positive,
                        'negative' => $curl_response->negative,
                     ]);
                }
            }
            //dd($curl_response);

        }else{
            dd("Lyrics not in English");
        }
        session()->flash('message', 'Music Update Success');
    }
}
