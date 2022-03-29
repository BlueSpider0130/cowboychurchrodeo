<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Contestant;
use App\Competition;
use App\CompetitionEntry;
use App\CompetitionInstance;
use App\Rodeo;
use App\RodeoEntry;

class EntryService
{

    public function enterCompetition( Contestant $contestant, Competition $competition, CompetitionInstance $instance = null,  array $data )
    {
        // check max enteries for contestant
        $maxEntries = $competition->allow_multiple_entries_per_contestant  ?  $competition->max_entries_per_contestant  :  1;

        if( $maxEntries )
        {
            $entryCount = CompetitionEntry::where('contestant_id', $contestant->id)->where('competition_id', $competition->id)->count();

            if( $entryCount >= $maxEntries )
            {
                throw new \Exception("Cannot enter contestant. Contestant is already entered for the maximum allowed entries.", 1);
            }
        }

        // check instance belongs to competition
        if( $instance  &&  $competition->id != $instance->competition_id )
        {
            throw new \Exception("Invalid instance. The instance must be for the competition.", 1);
        }


        DB::beginTransaction();

        try 
        {
            // create rodeo entry if there is not one
            if( $competition->rodeo  &&  RodeoEntry::where('contestant_id', $contestant->id)->where('rodeo_id', $competition->rodeo->id)->count() < 1 )
            {
                $rodeoEntry = $this->createRodeoEntry( $contestant, $competition->rodeo );
            }

            $data['instance_id'] = $instance->id;

            // create competition entry
            $competitionEntry = $this->createCompetitionEntry( $contestant, $competition, $data );

            DB::commit();            

            return $competitionEntry;
        } 
        catch ( \Exception $e ) 
        {
            DB::rollBack();
            throw $e;            
        }
    }


    protected function createRodeoEntry( Contestant $contestant, Rodeo $rodeo, array $data = [] )
    {
        if( null === $contestant->id )
        {
            throw new \Exception("Contestant missing id.", 1);
        }

        if( null === $rodeo->id )
        {
            throw new \Exception("Rodeo missing id.", 1);            
        }

        if( $contestant->organization_id != $rodeo->organization_id )
        {
            throw new \Exception("Contestant and rodeo do not belong to the same organization.", 1);            
        }

        return  RodeoEntry::create([
                        'contestant_id' => $contestant->id, 
                        'rodeo_id' => $rodeo->id,
                    ]);
    }

    public function createCompetitionEntry( Contestant $contestant, Competition $competition, array $data = [] )
    {
        if( null === $contestant->id )
        {
            throw new \Exception("Contestant missing id.", 1);
        }

        if( null === $competition->id )
        {
            throw new \Exception("Competition missing id.", 1);            
        }

        if( $contestant->organization_id != $competition->organization_id )
        {
            throw new \Exception("Contestant and competition do not belong to the same organization.", 1);            
        }
     
        $data['position'] = isset($data['position'])  &&  $data['position']  ?  $data['position']  :  null;

        if( isset($data['position'])  && !in_array($data['position'], ['header', 'heeler']) )
        {
            throw new \Exception('Invalid position. Position should be "header", "heeler", or null.', 1);
        }

        return  CompetitionEntry::create([
                        'competition_id' => $competition->id,
                        'contestant_id' => $contestant->id,
                        'position' => isset($data['position']) ? $data['position'] : null, 
                        'no_fee' => isset($data['no_fee']) ? true : false, 
                        'no_score' => isset($data['no_score']) ? true : false,
                        //'contestant_requests' => isset($data['contestant_requests']) ? $data['contestant_requests'] : null
                        'instance_id' => $data['instance_id']

                    ]);
    }


    public function removeCompetitionEntry( CompetitionEntry $entry )
    {
        $contestantId = $entry->contestant_id;
        $competition = $entry->competition;
        $rodeoId = $competition->rodeo_id;
        $competitionIds = Competition::where('rodeo_id', $rodeoId)->pluck('id')->toArray();

        $entry->delete();

        if( CompetitionEntry::whereIn('competition_id', $competitionIds)->where('contestant_id', $contestantId)->count() < 1 )
        {
            RodeoEntry::where('contestant_id', $contestantId)->where('rodeo_id', $rodeo->id)->delete();
        }
    }
}
