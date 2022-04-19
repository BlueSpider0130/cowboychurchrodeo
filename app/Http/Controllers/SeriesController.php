<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Series;

class SeriesController extends Controller
{
    public function show( Organization $organization, Series $series )
    {
        
        $inProgress = $organization
                    ->rodeos()
                    ->current()
                    ->orderBy('starts_at')
                    ->get();

        $scheduled = $organization
                    ->rodeos()
                    ->scheduled()
                    ->orderBy('starts_at')
                    ->get();

        $ended = $organization
                    ->rodeos()
                    ->ended()
                    ->orderBy('starts_at')
                    ->get();
        
        // dd($inProgress, $scheduled, $ended); exit();

        $data['organization'] = $organization;
        $data['series'] = $series;
        return view('series.show', $data);
    }
}
