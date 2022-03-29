<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportEntriesFile extends Command
{
    private $organization;
    private $rodeo;
    private $competitions;
    private $events;
    private $groups;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function choose(array $items, $title = 'item')
    {
        $id = null;

        while( null === $id )
        {
            $this->line(PHP_EOL);
            
            foreach( $items as $id => $name )
            {
                $this->line("  - [$id]  $name ");
            }

            $id = $this->ask("Choose $title: ");

            if( !array_key_exists($id, $items) )
            {
                $this->line('invalid choice...');
                $id = null;
            }
        }

        return $id;
    }

    public function continue()
    {
        $response = $this->ask('Continue [y/n]? ');

        if ($response) {
            $response = strtolower($response);
            
            if ('y' == str_split($response)[0]) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // org
        $organizations = \App\Organization::pluck('name', 'id')->toArray();
        $id = $this->choose($organizations, 'organization');
        $organization = \App\Organization::find($id);
        $this->organization = $organization;

        // rodeo
        $rodeos = $organization->rodeos()->orderBy('starts_at', 'desc')->pluck('name', 'id')->toArray();
        $id = $this->choose($rodeos, 'rodeo');
        $rodeo = \App\Rodeo::find($id);
        
        // import file
        $files = array_diff( scandir(base_path('data')), ['.', '..'] );
        $index = $this->choose($files, 'file');
        $file = $files[$index];
        $path = base_path("data/{$file}");

        // summary
        $this->line(PHP_EOL."Importing $file to Rodeo: {$rodeo->name}".PHP_EOL);

        if( !$this->continue() )
        {
            $this->info('Exiting...');
            return null;
        }

        // set rodeo info
        $this->rodeo = $rodeo;
        $this->competitions = $rodeo->competitions;
        $this->events = $rodeo->competitions->pluck('event')->unique();
        $this->groups = $rodeo->competitions->pluck('group')->unique();

        // process
        if (($handle = fopen($path, "r")) !== false) 
        {
            while ( ($data = fgetcsv($handle, 1000, ",")) !== false ) 
            {
                $this->processRow($data);
            }
            fclose($handle);
        }     
        
        $this->info("Import complete");
    }


    public function findByName($collection, $name)
    {
        $result = null;

        foreach($collection as $item)
        {
            $itemName = trim(strtolower($item->name));
            $name = trim(strtolower($name));

            if($itemName == $name)
            {
                $result = $item;
                break;
            }
        }

        return $item;
    }


    private function processRow($data)
    {
        // echo data line... 
        $this->line(implode(' ', $data));

        // get data
        $day = \Carbon\Carbon::parse($data[0])->startOfDay();
        $lastName = $data[1];
        $firstName = $data[2];
        $groupName = $data[4];
        $eventName = $data[5];

        // find group and event
        $group = $this->findByName($this->groups, $groupName);
        
        if( null === $group )
        {
            $this->error("Group $groupName not found!");
            return null;
        }

        $event = $this->findByName($this->events, $eventName);
        
        if( null === $event )
        {
            $this->error("Event $eventName not found!");
            return null;
        }

        // find contestant 
        $contestant = $this->organization
                        ->contestants()
                            ->where('first_name', 'LIKE', $firstName)
                            ->where('last_name', 'LIKE', $lastName)
                            ->first();
                    
        if( !$contestant )
        {
            $this->error("Contestant not found: $lastName, $firstName");
            return null;
        }

        // find competition
        $competition = $this->competitions->where('event_id', $event->id)->where('group_id', $group->id)->first();

        if( !$competition )
        {
            $this->error("Could not find competition for: {$group->name} - {$event->name}");
            return null;
        }

        $instance = $competition->instances->where('starts_at', $day)->first();

        if( !$instance )
        {
            $this->error("No competition for day: ".$day);
            return null;
        }
        
        // check for entry
        $entry = $this->rodeo
                    ->competition_entries()
                        ->where('contestant_id', $contestant->id)
                        ->where('competition_id', $competition->id)
                        ->where('instance_id', $instance->id)
                        ->first();

        if( $entry )
        {
            $this->info('Already entered into this event. ');
        }
        else
        {
            $entry = \App\CompetitionEntry::create([
                'contestant_id' => $contestant->id, 
                'competition_id' => $competition->id, 
                'instance_id' => $instance->id,
            ]);

            $this->info("$lastName, $firstName entered into $groupName, $eventName");
        }

        // check for rodeo entry
        $rodeoEntry = $this->rodeo->entries()->where('contestant_id', $contestant->id)->first();

        if( $rodeoEntry )
        {
            $this->info('Already entered in rodeo');
        }
        else
        {
            \App\RodeoEntry::create([
                'contestant_id' => $contestant->id,
                'rodeo_id' => $this->rodeo->id
            ]);
        }

        $this->line(PHP_EOL);
    }
}
