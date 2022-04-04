<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Competition;

class ResultsController extends Controller
{
    public function show(Organization $organization, Competition $competition)
    {
       $data['organization'] = $organization;
       $data['competition'] = $competition;

       $direction = 'time' == $competition->event->result_type ? 'asc' : 'desc';
       $data['results'] = $competition
                            ->entries()
                                ->whereNotNull('score')
                                ->orderBy('score', $direction)
                                ->with(['contestant'])
                                ->get();
        $data['pending'] = $competition
                            ->entries()
                                ->whereNull('score')
                                ->with(['contestant'])
                                ->get()
                                ->sortBy('contestant.last_name');
        $data['checkedInIds'] = \App\RodeoEntry::where('rodeo_id', $competition->rodeo_id)
                                    ->whereNotNull('checked_in_at')
                                    ->pluck('contestant_id')
                                    ->toArray();
        
       return view('results.show', $data);
    }
}
