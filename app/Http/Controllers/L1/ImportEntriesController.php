<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportEntriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function home()
    {
        //$data['rodeos'] = \App\Rodeo::where('ends_at', '>', \Carbon\Carbon::now())->get();
        $data['rodeos'] = \App\Rodeo::get();
        return view('L1.import_home', $data);
    }

    public function import(\App\Rodeo $rodeo)
    {
        $dates = [];

        foreach( $rodeo->competitions as $competition )
        {
            foreach($competition->instances as $instance)
            {
                $date = $instance->starts_at;

                if( !in_array($date, $dates) )
                {
                    $dates[] = $date;
                }
            }
        }

        $names = [];
        foreach( $rodeo->organization->contestants as $contestant )
        {
            $names[] = strtoupper("{$contestant->last_name}, {$contestant->first_name}");
        }

        $data['rodeo'] = $rodeo;
        $data['dates'] = $dates;
        $data['groups'] = array_map('strtoupper', $rodeo->organization->groups->pluck('name')->toArray());
        $data['events'] = array_map('strtoupper', $rodeo->organization->events->pluck('name')->toArray());
        $data['names'] = $names;
        return view('L1.import_import', $data);
    }

    
    public function process(\App\Rodeo $rodeo, Request $request)
    {
        $rodeo->with([
            'organization', 
            'organization.groups',
            'organization.events',
            'organization.contestants',
            'entries',
            'competition_entries'
        ]);

        $request->validate([
            'day' => 'required', 
            'data' => ['required', 'json'],
        ]);

        $day = \Carbon\Carbon::parse( $request->input('day') );

        $contestants = [];
        foreach($rodeo->organization->contestants as $contestant)
        {
            $key = strtoupper("{$contestant->last_name}, {$contestant->first_name}");
            $contestants[$key] = $contestant;
        }

        $groups = [];
        foreach($rodeo->organization->groups as $group)
        {
            $key = strtoupper($group->name);
            $groups[$key] = $group;
        }

        $events = [];
        foreach($rodeo->organization->events as $event)
        {
            $key = strtoupper($event->name);
            $events[$key] = $event;
        }

        $rodeoEntryContestantIds = $rodeo->entries->pluck('contestant_id')->toArray();
        $results = [];

        $importEntries = json_decode( $request->input('data') );

        foreach( $importEntries as $importEntry )
        {
            $lastName = $importEntry->lastname;
            $firstName = $importEntry->firstname;
            $groupName = $importEntry->group;
            $eventName = $importEntry->event;
            $comments = $importEntry->comments;

            $result = [
                'data' => $importEntry,
                'errors' => [],
                'entry' => null,
                'log' => []
            ];

            $contestantKey = strtoupper("{$lastName}, {$firstName}");
            $contestant = array_key_exists($contestantKey, $contestants) ? $contestants[$contestantKey] : null;

            if( !$contestant )
            {
                $result['errors'][] = 'Contestant does not exist';
            }

            $groupKey = strtoupper($groupName);
            $group = array_key_exists($groupKey, $groups) ? $groups[$groupKey] : null;

            if( !$group )
            {
                $result['errors'][] = 'Group does not exist';
            }

            $eventKey = strtoupper($eventName);
            $event = array_key_exists($eventKey, $events) ? $events[$eventKey] : null;

            if( !$event )
            {
                $result['errors'][] = 'Event does not exist';
            }

            if ($group && $event) {
                $competition = $rodeo
                                    ->competitions
                                    ->where('group_id', $group->id)
                                    ->where('event_id', $event->id)
                                    ->first();
                if (!$competition) {
                    $result['errors'][] = "Competition {$group->name} {$event->name} does not exist for this rodeo.";
                }
            }

            if( count($result['errors']) > 0 )
            {
                $results[] = $result;
                continue;
            }

            // check if contestant has rodeo entry
            if( !in_array($contestant->id, $rodeoEntryContestantIds) )
            {
                \App\RodeoEntry::create([
                    'contestant_id' => $contestant->id,
                    'rodeo_id' => $rodeo->id
                ]);
                $rodeoEntryContestantIds[] = $contestant->id;
                $result['log'][] = "{$contestant->last_name}, {$contestant->first_name} entered into rodeo";
            }

            // enter into competition
            
            $entries = $rodeo
                        ->competition_entries()
                        ->where('contestant_id', $contestant->id)
                        ->where('competition_id', $competition->id)
                        ->get();
            
            if($entries->count() > 0)
            {
                $result['error'][] = "Already registered for competition {$group->name} {$event->name}";
            }
            else
            {
                $instance = $competition->instances
                                ->where('starts_at', $day)
                                ->first();
                if(!$instance)
                {
                    $result['error'][]  = "Event competition not available on this date...";
                }
                else
                {
                    \App\CompetitionEntry::create([
                        'contestant_id' => $contestant->id,
                        'competition_id' =>$competition->id,
                        'instance_id' => $instance->id
                    ]);
                    $result['log'][] = "{$contestant->last_name}, {$contestant->first_name} entered into competition {$competition->id} : {$competition->group->name} {$competition->event->name}";
                }
            }

            $results[] = $result;
        }

        $data['rodeo'] = $rodeo;
        $data['day'] = \Carbon\Carbon::parse( $request->input('day') );
        $data['results'] = $results;
        return view('L1.import_results', $data);
        
    }

//     public function process(\App\Rodeo $rodeo, Request $request)
//     {`
//         $request->validate([
//             'day' => 'required', 
//             'data' => ['required', 'json'],
//         ]);

//         $day = \Carbon\Carbon::parse( $request->input('day') );

//         $competitions = $rodeo->competitions;
//         $events = $competitions->pluck('event')->unique();
//         $groups = $competitions->pluck('group')->unique();

//         $data = $request->input('data');

//         $entries = json_decode($data);

//         $results = [];

//         foreach( $entries as $entry )
//         {
//             $row  = new \stdClass;
//             $row->entry = $entry;
//             $row->status = null;
//             $row->message = '';

//             $group = $groups->where('name', $entry->group)->first();
    
//             if( !$group )
//             {
//                 $row->status = 'error';
//                 $row->message .= 'There are no groups with this name. ';
//                 $results[] = $row;
//                 continue;
//             }

//             $event = $events->where('name', $entry->event)->first();

//             if( !$event )
//             {
//                 $row->status = 'error';
//                 $row->message .= 'There are no events with this name. ';
//                 $results[] = $row;
//                 continue;
//             }

//             $competition = $competitions->where('event_id', $event->id)->where('group_id', $group->id)->first();

//             if( !$competition )
//             {
//                 $row->status = 'error';
//                 $row->message .= 'Could not find competition. ';
//                 $results[] = $row;
//                 continue;                
//             }

//             $instance = $competition->instances->where('starts_at', $day)->first();

//             if( !$instance )
//             {
//                 $row->status = 'error';
//                 $row->message .= 'Day not available for group/event. ';
//                 $results[] = $row;
//                 continue; 
//             }

//             $name = $entry->name;
//             $parts = explode(',', $name); 
//             $last = isset($parts[0]) ? trim($parts[0]) : null;
//             $first = isset($parts[1]) ? trim($parts[1]) : null;

//             if( !$first )
//             {
//                 $row->status = 'error';
//                 $row->message .= 'Missing first name. ';
//                 $results[] = $row;
//                 continue;
//             }

//             if( !$last )
//             {
//                 $row->status = 'error';
//                 $row->message .= 'Missing last name. ';
//                 $results[] = $row;
//                 continue;
//             }

//             $contestant = \App\Contestant::where('organization_id', $rodeo->organization_id)->where('first_name', 'LIKE', $first)->where('last_name', 'LIKE', $last)->first();

//             if( !$contestant )
//             {
//                 $row->message .= "conestant not found.";
//                 continue;
//                 $contestant = \App\Contestant::create([
//                     'first_name' => $first,
//                     'last_name' => $last, 
//                     'organization_id' => $rodeo->organization_id
//                 ]);

//                 $row->message .= "Contestant created! ";
//             }

//             $entry = $rodeo->competition_entries()->where('competition_id', $competition->id)->where('instance_id', $instance->id)->first();

//             if( $entry )
//             {
//                 $row->message .= 'Already entered into this event. ';
//             }
//             else
//             {
//                 $entry = \App\CompetitionEntry::create([
//                     'contestant_id' => $contestant->id, 
//                     'competition_id' => $competition->id, 
//                     'instance_id' => $instance->id,
//                 ]);

//                 $row->status = 'success';
//                 $row->message .= "Entered into competition. ";
//             }

//             $rodeoEntry = $rodeo->entries()->where('contestant_id', $contestant->id)->first();

//             if( !$rodeoEntry )
//             {
//                 $rodeoEntry = \App\RodeoEntry::create([
//                     'contestant_id' => $contestant->id,
//                     'rodeo_id' => $rodeo->id,  
//                 ]);

//                 $row->status = 'success';
//                 $row->message .= "Entered into rodeo. ";
//             }

//             $results[] = $row;
//         }
// return $data;
//         //$data = json_encode($results);

//         // return redirect()->route('L1.import.results', [$rodeo, 'data' => $data, 'day' => $day->timestamp]);
//     }


//     public function results( Request $request, \App\Rodeo $rodeo )
//     {
//         $day = $request->input('day') ? \Carbon\Carbon::createFromTimestamp($request->input('day')) : null; 

//         $string = $request->input('data');
//         $results = $string ? json_decode($string) : null;
       
//         $data['rodeo'] = $rodeo;
//         $data['day'] = $day;
//         $data['results'] = $results;

//         return view('L1.import_results', $data);
//     }
    
}
