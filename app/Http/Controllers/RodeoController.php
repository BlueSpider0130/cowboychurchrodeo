<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Rodeo;

class RodeoController extends Controller
{
    public function show( Organization $organization, Rodeo $rodeo )
    {
        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;
        $data['competitions'] = $rodeo->competitions()
                                        ->join('groups', 'groups.id', '=', 'competitions.group_id')
                                        ->join('events', 'events.id', '=', 'competitions.event_id')
                                        ->selectRaw('competitions.*, groups.name as group_name, events.name as event_name')
                                        ->with(['group', 'event'])
                                        ->orderBy('group_name')
                                        ->orderBy('event_name')
                                        ->get(); 

        return view('rodeos.show', $data);
    }
}
