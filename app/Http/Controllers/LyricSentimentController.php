<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\LyricSentiment;
use Illuminate\Http\Request;

class LyricSentimentController extends Controller
{
    public function getLyricSentiment(Request $request){
    
        $songId = $request->songId;
        $sentiments = DB::select('select * from lyric_sentiments WHERE songId = ?',[$songId]);
        return response()->json(['status'=> '200', 'message'=> 'Sentiments Retrieved','sentiments'=> $sentiments]);
    }

}
