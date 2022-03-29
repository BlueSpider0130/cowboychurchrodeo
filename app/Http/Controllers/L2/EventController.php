<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Organization;
use App\Event;

class EventController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, Organization $organization )
    {
        $events = $organization->events()->orderBy('name')->get();

        if( $request->wantsJson() )
        {
            return $events;
        }

        $data['organization'] = $organization;
        $data['events'] = $events;

        return view('L2.events.events_index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Organization $organization)
    {
        return view('L2.events.events_create')
                ->with('organization', $organization);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                ( new \App\Rules\Unique( Event::class ) )->forOrganization( $organization )
            ],

            'description' => [
                'nullable', 
                'string'
            ], 

            'team_roping' => [
                'nullable'
            ],

            'result_type' => [
                'nullable'
            ]
        ]);

        $event = Event::create([
                'organization_id' => $organization->id, 
                'name' => $validated['name'],
                'description' => $validated['description'], 
                'team_roping' => isset($validated['team_roping']) ? true : false, 
                'result_type' => $validated['result_type']
        ]);

        return redirect()
                ->route('L2.events.index', $organization)
                ->with('successAlert', "Event \"{$event->name}\" added.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Event $event)
    {
        abort(400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization, Event $event)
    {
        return view('L2.events.events_edit')
                ->with('organization', $organization)
                ->with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization, Event $event)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                ( new \App\Rules\Unique( 'events' ) )->forOrganization( $organization )->ignore( $event )
            ],

            'description' => [
                'nullable', 
                'string'
            ], 

            'team_roping' => [
                'nullable'
            ],
            
            'result_type' => [
                'nullable'
            ]
        ]);

        $validated['team_roping'] = isset($validated['team_roping']) ? true : false;

        $event->update( $validated );


        return redirect()
                ->route('L2.events.index', $organization)
                ->with('successAlert', "Event \"{$event->name}\" updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Event $event)
    {
        if( !Gate::allows('access-level-2-for-record', $event) )
        {
            abort(403);
        }

        $event->delete();

        return redirect()
                ->route('L2.events.index', $organization)
                ->with('successAlert', "Event \"{$event->name}\" deleted.");
    }
}
