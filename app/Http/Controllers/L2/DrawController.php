<?php

namespace App\Http\Controllers\L2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Organization;
use App\Rodeo;

class DrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level-2');
    }

    public function home( Organization $organization, Rodeo $rodeo )
    {
        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;

        if( $rodeo->competition_entries()->whereNotNull('draw')->count() > 0 )
        {
            return view('L2.draw.complete', $data);
        }

        return view('L2.draw.home', $data);
    }

    public function create( Organization $organization, Rodeo $rodeo )
    {
        $data['organization'] = $organization;
        $data['rodeo'] = $rodeo;

        if( $rodeo->competition_entries()->whereNotNull('draw')->count() > 0 )
        {
            return view('L2.draw.complete', $data);
        }

        if( $rodeo->competition_entries()->whereNulL('instance_id')->count() > 0 )
        {
            $data['message'] = "Cannot create draw until all entries have been assigned to a performace day.";
            return view('L2.draw.error', $data);
        }

        $competitionIds = $rodeo
                            ->competitions()
                            ->pluck('id')
                            ->toArray();

        $instanceIds = \App\CompetitionInstance::select()
                            ->whereIn('competition_id', $competitionIds)
                            ->pluck('id')
                            ->toArray();
        
        DB::transaction(function () use ($rodeo, $instanceIds) {     
            foreach($instanceIds as $instanceId)
            {
                $entries = $rodeo
                            ->competition_entries()
                            ->where('instance_id', $instanceId)
                            ->inRandomOrder()
                            ->get();

                foreach($entries as $key => $entry)
                {
                    $draw = $key + 1;
                    $entry->draw = $draw;
                    $entry->save();
                }
            }
        });

        return view('L2.draw.complete', $data);
    }
}
