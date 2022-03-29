<?php

namespace App\Services;

use App\Contestant;
use App\RodeoEntry;


class ContestantService 
{
    static function delete( Contestant $contestant )
    {
        $contestant->users()->sync([]);

        if( self::canBeDeleted( $contestant ) )
        {
            $contestant->delete();
        }            
    }


    static function canBeDeleted( Contestant $contestant )
    {        
        $rodeoEntries = RodeoEntry::with(['rodeo'])
                            ->where('contestant_id', $contestant->id)
                            ->get();

        // Do not delete if already checked into rodeo
        if( $rodeoEntries->whereNotNull('checked_in_at')->count() )
        {
            $reason = "Contestant has already been checked into rodeo.";            
            return false;
        }

        // Do not delete if in a rodeo that has closed, started, or ended       
        foreach ($rodeoEntries as $entry) 
        {
            // Do not delete if registered for a rodeo where the close out date has passed 
            if( $entry->rodeo->closes_at  &&  $entry->rodeo->closes_at < \Carbon\Carbon::now() )
            {
                $reason = "Contestant is registered for a rodeo that has already closed.";

                return false;
            }

            // Do not delete if registered for a rodeo that has started or ended
            if( $entry->rodeo->starts_at  &&  $entry->rodeo->starts_at < \Carbon\Carbon::now() )
            {
                $reason = "Contestant is registered for a rodeo that has already started.";

                if( $entry->rodeo->ends_at  &&  $entry->rodeo->ends_at < \Carbon\Carbon::now() )
                {
                    $reason = "Contestant is registered for a rodeo that has already ended.";
                }

                return false;
            }
        }

        // Check competition entries...

        $entries = $contestant
                    ->competition_entries()
                    ->with(['competition', 'competition.rodeo', 'instance'])
                    ->get();

        // Check by checking if any instance is in the past
        foreach( $entries->pluck('instance') as $instance )
        {
            if( $instance->starts_at  &&  $instance->starts_at < \Carbon\Carbon::now() )
            {
                $reason = "Contestant is registered for a competition instance that has already ended.";
                return false;
            }
        }

        // Do not delete if registered for a competition that is part of a rodeo that has closed, started, or ended.
        foreach( $entries->pluck('competition') as $competition )
        {
            // Do not delete if registered for a rodeo where the close out date has passed 
            if( $competition->rodeo->closes_at  &&  $competition->rodeo->closes_at < \Carbon\Carbon::now() )
            {
                $reason = "Contestant is registered for a rodeo that has already closed.";

                return false;
            }            

            // Do not delete if registered for a rodeo that has started or ended
            if( $competition->rodeo->starts_at  &&  $competition->rodeo->starts_at < \Carbon\Carbon::now() )
            {
                $reason = "Contestant is registered for a rodeo that has already started.";

                if( $competition->rodeo->ends_at  &&  $competition->rodeo->ends_at < \Carbon\Carbon::now() )
                {
                    $reason = "Contestant is registered for a rodeo that has already ended.";
                }

                return false;
            }
        }

        return true;    
    }

}
