<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process users from file contents';

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

        $data = json_decode($contents);

        if( !$data )
        {
            $this->error('No data to process!');

            return 0;
        }

        if( !is_array($data) )
        {
            $this->error('Invalid data!');

            return 0;
        }

        foreach( $data as $rowData )
        {
            dd( $rowData );
        }

        
    }
}
