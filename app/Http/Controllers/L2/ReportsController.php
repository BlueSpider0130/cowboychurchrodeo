<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Series;
use App\Rodeo;
use App\User;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-2');
    }

    public function home( Organization $organization )
    {
        $data['organization'] = $organization;

        $data['series_collection'] = $organization
                                        ->series()
                                        ->orderBy('starts_at', 'desc')
                                        ->orderBy('ends_at', 'desc')
                                        ->with(['rodeos'])
                                        ->get();

        return view('L2.reports.home', $data);
    }


    public function emails( Organization $organization )
    {
        $contestants = $organization->contestants()
                                    ->with(['users', 'users.contestants'])
                                    ->get();
        $users = [];

        foreach( $contestants as $contestant )
        {
            foreach($contestant->users as $user)
            {
                $users[$user->id] = $user;
            }
        }

        $users = collect($users)->sortBy('last_name');

        return view('L2.reports.emails')
                ->with('organization', $organization)
                ->with('users', $users);
    }

    
    public function selectRodeo( Organization $organization, Series $series)
    {
        $data['organization'] = $organization;
        $data['series'] = $series;
        $data['rodeos'] = $series->rodeos()->orderBy('starts_at')->get();

        return view('L2.reports.rodeos', $data);
    }

    public function selectEntriesDay( Organization $organization, Rodeo $rodeo )
    {
        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;

        if( $rodeo->competition_entries->count() < 1 )
        {
            return view('L2.reports.no_entries')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('active', 'entries');
        }

        return view('L2.reports.entries_days', $data);  
    }


    public function entries( Organization $organization, Rodeo $rodeo )
    {
        $entries = $rodeo
                    ->competition_entries()
                    ->with(['contestant', 'competition', 'competition.event', 'competition.group'])
                    ->get()
                    ->sortBy('contestant.last_name');

        if( $entries->count() < 1 )
        {
            return view('L2.reports.no_entries')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('active', 'entries');
        }

        $data = [];

        foreach( $entries as $entry )
        {
            $key = $entry->contestant->id;

            if( !array_key_exists($key, $data) )
            {
                $data[$key] = [];
                $data[$key]['contestant'] = $entry->contestant;
                $data[$key]['dates'] = [];
                $data[$key]['groups'] = [];
                $data[$key]['events'] = [];
            }
            
            if( $entry->instance  &&  $entry->instance->starts_at )
            {
                $ts = $entry->instance->starts_at->timestamp;
                if( !in_array($ts, $data[$key]['dates']) )
                {
                    $data[$key]['dates'][] = $ts;
                }
            }

            $name = $entry->competition->group->name;

            if( !in_array($name, $data[$key]['groups']) )
            {
                $data[$key]['groups'][] = $name;
            }

            $name = $entry->competition->event->name;

            if( !in_array($name, $data[$key]['events']) )
            {
                $data[$key]['events'][] = $name;
            }
        }

        return view('L2.reports.entries')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('data', $data);
    }


    public function selectDrawDay( Organization $organization, Rodeo $rodeo )
    {
        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;
  
        if( $rodeo->competition_entries->count() < 1 )
        {
            return view('L2.reports.no_entries')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('active', 'draw');
        }

        return view('L2.reports.draw_days', $data);  
    }


    public function draw( Request $request, Organization $organization, Rodeo $rodeo, $i)
    {
        if( $rodeo->competition_entries->count() < 1 )
        {
            return view('L2.reports.no_entries')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('active', 'draw');
        }

        $drawExists = $rodeo
                        ->competition_entries()
                        ->whereNotNull('draw')
                        ->count() > 0 ? true : false;

        if( false === $drawExists )
        {
            return redirect()->route('L2.draw.home', [$organization, $rodeo]);
        }

        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;

        $j = $i - 1;
        $data['day'] = $rodeo->starts_at->copy()->addDays($j);
        $data['competitions'] = $rodeo->competitions()
                                    ->selectRaw('competitions.*, groups.name as group_name, events.name as event_name')
                                    ->join('groups', 'groups.id', 'competitions.group_id')
                                    ->join('events', 'events.id', 'competitions.event_id')
                                    ->orderBy('group_name')
                                    ->orderBy('event_name')
                                    ->get();

        $instanceIds = $rodeo->competition_instances()
                                ->where('starts_at', $data['day'])
                                ->pluck('competition_instances.id')
                                ->toArray();

        if( count($instanceIds) < 1 )
        {
            return "invalid day";
        }

        $data['entries'] = $rodeo->competition_entries()
                    ->whereIn('instance_id', $instanceIds)
                    ->with('contestant')
                    ->get();

        if( $request->input('print') )
        {
            return view('L2.reports.draw.print', $data);
        }

        return view('L2.reports.draw', $data);
    }

    public function selectJudgeDay( Organization $organization, Rodeo $rodeo )
    {
        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;
  
        if( $rodeo->competition_entries->count() < 1 )
        {
            return view('L2.reports.no_entries')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('active', 'judge');
        }

        return view('L2.reports.judge_days', $data);  
    }


    public function judge( Request $request, Organization $organization, Rodeo $rodeo, $i )
    {
        if( $rodeo->competition_entries->count() < 1 )
        {
            return view('L2.reports.no_entries')
                ->with('organization', $organization)
                ->with('rodeo', $rodeo)
                ->with('active', 'draw');
        }

        $drawExists = $rodeo
                        ->competition_entries()
                        ->whereNotNull('draw')
                        ->count() > 0 ? true : false;

        if( false === $drawExists )
        {
            return redirect()->route('L2.draw.home', [$organization, $rodeo]);
        }

        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;

        $j = $i - 1;
        $data['day'] = $rodeo->starts_at->copy()->addDays($j);

        $data['competitions'] = $rodeo->competitions()
                                    ->selectRaw('competitions.*, groups.name as group_name, events.name as event_name')
                                    ->join('groups', 'groups.id', 'competitions.group_id')
                                    ->join('events', 'events.id', 'competitions.event_id')
                                    ->orderBy('group_name')
                                    ->orderBy('event_name')
                                    ->get();

        $instanceIds = $rodeo->competition_instances()
                                ->where('starts_at', $data['day'])
                                ->pluck('competition_instances.id')
                                ->toArray();
                       
        if( count($instanceIds) < 1 )
        {
            return "invalid day";
        }

        $data['entries'] = $rodeo->competition_entries()
                    ->whereIn('instance_id', $instanceIds)
                    ->with('contestant')
                    ->get();

        if( $request->input('print') )
        {
            return view('L2.reports.judge.print', $data);
        }

        return view('L2.reports.judge', $data);
    }



}
