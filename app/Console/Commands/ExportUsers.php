<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:users {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export users.';

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
        $users = \App\User::all()->toJson();

        if( $path = $this->argument('path') )
        {
            dump( file_exists($path) );
        }
        else
        {
            echo $users;
        }
    }
}
