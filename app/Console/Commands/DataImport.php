<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DataImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process old data';



    private $userMap = [];
    private $newOrganization; 


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if( $path = $this->argument('path') )
        {
            if( !file_exists($path) )
            {
                $this->error('File not found!');

                return 0;
            }

            $contents = file_get_contents($path);
        }
        else
        {
            $contents = file_get_contents('php://stdin');
        }        

        $old = json_decode($contents);

        if( !$old )
        {
            $this->error('No data to process!');

            return 0;
        }

        // Users
        $userMap = [];

        if( isset($old->users) )
        {
            $newUserEmails = \App\User::pluck('id', 'email')->toArray();

            $createCount = 0;

            foreach( $old->users as $oldUser )
            {
                $oldId = $oldUser->id; 

                if( array_key_exists($oldUser->email, $newUserEmails) )
                {
                    $newId = $newUserEmails[$oldUser->email];

                    $userMap[$oldId] = $newId;

                    $this->line("User {$oldUser->email} already exists!");
                }
                else
                {
                    $newUser = \App\User::create([
                        'first_name' => $oldUser->first_name ? $oldUser->first_name : '',
                        'last_name' => $oldUser->last_name ? $oldUser->last_name : '',
                        'email' => $oldUser->email,
                        'password' => $oldUser->password, 
                        'admin' => $oldUser->is_admin,
                    ]);

                    $userMap[$oldId] = $newUser->id;

                    $createCount++;
                }
            }

            $this->info("$createCount users created.");
        }


        // Tasks 
        if( isset($old->priorities)  &&  isset($old->statuses)  &&  isset($old->types)  &&  isset($old->tasks) )
        {
            $modelIds = \App\AdminTaskPriority::pluck('id')->toArray();
            foreach( $old->priorities as $model )
            {
                if( !in_array($model->id, $modelIds) )
                {
                    \App\AdminTaskPriority::create([
                        'id' => $model->id, 
                        'name' => $model->name
                    ]);
                }
            }

            $modelIds = \App\AdminTaskStatus::pluck('id')->toArray();
            foreach( $old->statuses as $model )
            {
                if( !in_array($model->id, $modelIds) )
                {
                    \App\AdminTaskStatus::create([
                        'id' => $model->id, 
                        'name' => $model->name
                    ]);
                }
            }

            $modelIds = \App\AdminTaskType::pluck('id')->toArray();
            foreach( $old->types as $model )
            {
                if( !in_array($model->id, $modelIds) )
                {
                    \App\AdminTaskType::create([
                        'id' => $model->id, 
                        'name' => $model->name
                    ]);
                }
            }

            $modelIds = \App\AdminTask::pluck('id')->toArray();
            foreach( $old->tasks as $model )
            {
                if( !in_array($model->id, $modelIds) )
                {
                    $userId = array_key_exists($model->created_by_user_id, $userMap) ? $userMap[$model->created_by_user_id] : null;

                    \App\AdminTask::create([
                        'id' => $model->id, 
                        'status_id' => $model->status_id,
                        'type_id' => $model->type_id,
                        'priority_id' => $model->priority_id,
                        'created_by_user_id' => $userId,
                        'page' => $model->page,
                        'description' => $model->description,
                        'closed' => $model->closed,
                        'created_at' => $model->created_at,
                    ]);
                }
            }
        }


        // Organization 
        $newOrganization = null;

        if( isset($old->organization) )
        {
            $newOrganization = \App\Organization::where('name', $old->organization->name)->first();

            if( !$newOrganization )
            {
                $newOrganization = \App\Organization::create([
                    'name' => $old->organization->name, 
                ]);

                $this->info('Organization created!');
            }

            // Secretary 
            if( isset($old->organization->secretary) )
            {
                $oldUser =  $old->organization->secretary;
                $oldSecretaryId = $oldUser->id;
                $newSecretaryId = array_key_exists($oldSecretaryId, $userMap) ? $userMap[$oldSecretaryId] : null;
                
                if( !$newSecretaryId )
                {
                    $secretary = \App\User::create([
                        'first_name' => $oldUser->first_name ? $oldUser->first_name : '',
                        'last_name' => $oldUser->last_name ? $oldUser->last_name : '',
                        'email' => $oldUser->email,
                        'password' => $oldUser->password, 
                        'admin' => $oldUser->is_admin,
                    ]);

                    $newSecretaryId = $secretary->id;

                    $this->info('Secretary user created!');
                }

                if( \App\UserLevel::where('user_id', $newSecretaryId)->where('organization_id', $newOrganization->id)->where('level', 2)->count() < 1 )
                {
                    \App\UserLevel::create([
                        'user_id' => $newSecretaryId,
                        'organization_id' => $newOrganization->id,
                        'level' => 2, 
                    ]);

                    $this->info('Secretary assigned level 2 for organization.');
                }
            }
        }


        // Contestants 
        if( isset($old->contestants)  &&  $newOrganization )
        {
            $count = 0;
            $syncCount = 0;

            foreach($old->contestants as $oldContestant)
            {
                $contestant = \App\Contestant::where('organization_id', $newOrganization->id)
                                ->where('first_name', $oldContestant->first_name)
                                ->where('last_name', $oldContestant->last_name)->first();

                if( !$contestant )
                {
                    $contestant = \App\Contestant::create([
                        'organization_id' => $newOrganization->id,
                        'first_name' => $oldContestant->first_name,
                        'last_name' => $oldContestant->last_name,
                        'birthdate' => $oldContestant->birthdate,
                        //'photo_path'
                        'address_line_1' => $oldContestant->address_line_1,
                        'address_line_2' => $oldContestant->address_line_2,
                        'city' => $oldContestant->city,
                        'state' => $oldContestant->state,
                        'postcode' => $oldContestant->postcode,
                    ]);

                    $count++;
                }

                if( isset($userMap[$oldContestant->user_id]) )
                {

                    $newUserId = $userMap[$oldContestant->user_id];

                    $contestant->users()->sync([$newUserId]);

                    $syncCount++;
                }
            }

            $this->info("$count contestants created!");
            $this->info("$syncCount contestants assigned to users.");
        }

/*
        // Series
        if( isset($old->organization->series)  &&  $newOrganization )
        {
            foreach( $old->organization->series as $oldSeries )
            {
                $newSeries = \App\Series::where('organization_id', $newOrganization)
                                ->where('starts_at', \Carbon\Carbon::parse($oldSeries->starts_at))
                                ->where('ends_at', \Carbon\Carbon::parse($oldSeries->ends_at))
                                ->first();

                if( !$newSeries )
                {
                    $newSeries = \App\Series::create([
                                        'organization_id' => $newOrganization->id,
                                        'name' => $oldSeries->name, 
                                        'membership_fee' => $oldSeries->membership_fee,
                                        'starts_at' => $oldSeries->starts_at,
                                        'ends_at' => $oldSeries->ends_at,
                                    ]);

                    $this->info('Series created.');
                }

                // events 
                $eventMap = [];

                if( $oldSeries->event_types )
                {
                    $count = 0;

                    foreach($oldSeries->event_types as $oldEvent)
                    {
                        $newEvent = $newOrganization->events->where('name', $oldEvent->name)->first();

                        if( !$newEvent )
                        {
                            $newEvent = \App\Event::create([
                                'organization_id' => $newOrganization->id,
                                'name'            => $oldEvent->name, 
                                'description'     => $oldEvent->description,
                                'team_roping'     => $oldEvent->team_roping,
                            ]);

                            $count++;
                        }

                        $eventMap[$oldEvent->id] = $newEvent->id;
                    }

                    $this->info("$count events created.");
                }

                // groups
                $groupMap = [];

                if( $oldSeries->groups )
                {
                    $count = 0;

                    foreach($oldSeries->groups as $oldGroup)
                    {
                        $newGroup = $newOrganization->groups->where('name', $oldGroup->name)->first();

                        if( !$newGroup )
                        {
                            $newGroup = \App\Group::create([
                                'organization_id' => $newOrganization->id,
                                'name'            => $oldGroup->name, 
                                'description'     => $oldGroup->description,
                            ]);
                        }

                        $groupMap[$oldGroup->id] = $newGroup->id;
                    }

                    $this->info("$count groups created.");
                }

                // rodeos
                if( isset($oldSeries->rodeos) )
                {
                    $newRodeos = $newSeries->rodeos;

                    $rodeoCount = 0;
                    foreach($oldSeries->rodeos as $oldRodeo )
                    {
                        $newRodeo = $newRodeos->where('starts_at', $oldRodeo->starts_at)->where('ends_at', $oldRodeo->ends_at)->first();

                        if( !$newRodeo )
                        {
                            $newRodeo = \App\Rodeo::create([
                                'organization_id' => $newOrganization->id,
                                'series_id' => $newSeries->id, 
                                'name' => $oldRodeo->name,
                                'office_fee' => 10, 
                                'starts_at' => $oldRodeo->starts_at,
                                'ends_at' => $oldRodeo->ends_at,
                                'opens_at' => $oldRodeo->registration_opens_at,
                                'closes_at' => $oldRodeo->registration_closes_at,
                            ]);

                            $rodeoCount++;
                        }

                        // competitions 
                        $competitionMap = [];
                        if( isset($oldRodeo->events) )
                        {
                            foreach($oldRodeo->events as $oldCompetition)
                            {
                                // need to map events and groups... 
                                $newCompetition = \App\Competition::create([
                                    'organization_id' => $newOrganization->id,
                                    'rodeo_id' => $newRodeo->id,
                                    'event_id' => $eventMap[$oldCompetition->event_type_id],
                                    'group_id' => $groupMap[$oldCompetition->group_id],
                                    'entry_fee' => $oldCompetition->price 
                                ]);

                                $competitionMap[$oldCompetition->id] = $newCompetition->id;
                            }
                        }
                    }

                    $this->info("$rodeoCount rodeos created.");
                }
            }
        }        
*/

        
    }


}
