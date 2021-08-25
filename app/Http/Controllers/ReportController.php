<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Report;

class ReportController extends Controller
{
    //
    public function report(Request $request){
    
        $report = new Report;
        $report->userId = $request->userId;
        $report->songId = $request->songId;
        $report->reportMessage = $request->reportMessage;
        $report->save();
        
        return response()->json(['status'=> '200', 'message'=> 'Report Successfully', 'extra' => 'null']);
    }

   
}
