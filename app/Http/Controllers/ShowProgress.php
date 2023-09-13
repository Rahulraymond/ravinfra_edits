<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress as ModelsProgress;
use App\Models\media;
class ShowProgress extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $year = ModelsProgress::first()->year;
        if(request('year') != null){
            $year = request('year');
        }
        
        $years = ModelsProgress::groupBy('year')->pluck('year');

        $responses = [];

            
        $months = ModelsProgress::where('year', $year)->groupBy('month')->pluck('month')->toArray();

        foreach ($months as $month) {

            $weeks = ModelsProgress::where('month', $month)->where('year',$year)->groupBy('week')->pluck('week')->toArray();

            foreach ($weeks as $key => $week) {

                $progress_ids = ModelsProgress::where('month', $month)->where('year',$year)->where('week',$week)->pluck('id')->toArray();

                $responses[$year][$month][$week]['media'] = media::whereIn('progress_id',$progress_ids)->pluck('caption','media')->toArray();
               
                $responses[$year][$month][$week]['desc'] = ModelsProgress::where('year',$year)->where('month',$month)->where('week',$week)->where('desc','!=',null)->value('desc');
            }
        }
        return view('frontend.progress.index',compact("years","responses"));
    }
}
