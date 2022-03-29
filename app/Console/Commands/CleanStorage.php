<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphan files.';

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
        // Remove contestant photos if path not in database
        $this->line('Cleaning orphan contestant photos...');

        $databasePaths = \Illuminate\Support\Facades\Schema::hasTable('contestants') ? \App\Contestant::pluck('photo_path')->toArray() : [];

        $directoryFiles = array_diff( scandir(storage_path('app/public/contestants')), ['.', '..'] );

        $count = 0;

        foreach ($directoryFiles as $filename) 
        {
            $path = "contestants/$filename";

            if( !in_array($path, $databasePaths) )
            {
                \Illuminate\Support\Facades\Storage::delete( "public/{$path}");   

                $count++;
            }
        }

        $this->info("Removed {$count} contestant files");


        // Remove document files if path not in database
        $this->line('Cleaning orphan document files...');

        $databasePaths = \Illuminate\Support\Facades\Schema::hasTable('documents') ? \App\Document::pluck('path')->toArray() : [];

        $directoryFiles = array_diff( scandir(storage_path('app/documents')), ['.', '..'] );

        $count = 0;

        foreach ($directoryFiles as $filename) 
        {
            $path = "documents/$filename";

            if( !in_array($path, $databasePaths) )
            {
                \Illuminate\Support\Facades\Storage::delete($path);   

                $count++;
            }
        }

        $this->info("Removed {$count} document files");
    }
}
