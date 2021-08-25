<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreController extends Controller
{
    public function getGenre(){
    
        $genres = DB::select('select * from genres');
        return response()->json(['status'=> '200', 'message'=> 'Genres','genres'=> $genres]); 
    }

}
