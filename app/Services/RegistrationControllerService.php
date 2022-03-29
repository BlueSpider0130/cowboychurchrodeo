<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Organization;
use App\Rodeo;
use App\Contestant;


/**
 * The L2 and L4 registration controllers have same functions but different views.
 * The business logic is placed here so that both L2 and L4 controllers can use it.
 */
class RegistrationControllerService
{

    static function showRegistration( Organization $organization, Rodeo $rodeo, Contestant $contestant, Request $request )
    {
        $rodeo->load(['entries', 'competition_entries']);

        $rodeoEntry = $rodeo
                        ->entries()
                        ->where('contestant_id', $contestant->id)
                        ->first();

        $competitionEntries = $rodeo
                    ->competition_entries()
                    ->with(['competition', 'competition.event', 'competition.group'])
                    ->where('contestant_id', $contestant->id)
                    ->get()
                    ->sort( function($a, $b) {
                        if( $a->competition->group->name === $b->competition->group->name ) 
                        {
                            if( $a->competition->event->name === $b->competition->event->name ) 
                            {
                                return 0;
                            }

                            return strnatcmp($a->competition->event->name, $b->competition->event->name);
                        } 

                        return strnatcmp($a->competition->group->name, $b->competition->group->name);
                    });


        $data['organization']       = $organization;
        $data['rodeo']              = $rodeo;
        $data['contestant']         = $contestant;
        $data['rodeoEntry']         = $rodeoEntry;
        $data['competitionEntries'] = $competitionEntries;

        return $data;
    }

    
}
