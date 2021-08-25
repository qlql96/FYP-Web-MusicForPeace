<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    //
    public function getTheme(){
    
        $themes = DB::select('select * from themes');
        return response()->json(['status'=> '200', 'message'=> 'Themes','themes'=> $themes]); 
    }
}
