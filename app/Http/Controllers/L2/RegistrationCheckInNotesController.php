<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\Contestant;
use App\Rodeo;
use App\RodeoEntry;

class RegistrationCheckInNotesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-2')->except('show');
        $this->middleware('level-3')->only('show');
    }


    public function edit( Organization $organization, Rodeo $rodeo, Contestant $contestant )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('contestant_id', $contestant->id)
                        ->first();

        if( !$rodeoEntry )
        {
            $rodeoEntry = RodeoEntry::create([
                'contestant_id' => $contestant->id,
                'rodeo_id' => $rodeo->id
            ]);
        }

        $data['organization']       = $organization;
        $data['rodeo']              = $rodeo;
        $data['contestant']         = $contestant;
        $data['rodeoEntry']         = $rodeoEntry;

        return view('L2.registration.check_in_notes_edit', $data);
    }


    public function update( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        if( $organization->id != $rodeo->organization_id )
        {
            abort( 404 );
        }

        if( $organization->id != $contestant->organization_id )
        {
            abort( 404 );
        }

        $validated = $request->validate([
            'notes' => [
                'nullable'
            ]
        ]);

        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('contestant_id', $contestant->id)
                        ->first();

        if( !$rodeoEntry )
        {
            RodeoEntry::create([
                'contestant_id' => $contestant->id,
                'rodeo_id' => $rodeo->id,
                'check_in_notes' => isset($validated['notes']) ? $validated['notes'] : null
            ]);
        }
        else
        {
            $rodeoEntry->update([
                'check_in_notes' => isset($validated['notes']) ? $validated['notes'] : null
            ]);
        }

        return redirect()
                ->route('L2.registration.show', [$organization, $rodeo, $contestant])
                ->with('successAlert', 'Registration updated.');
    }
}
