<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user from commmand line.';

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
        $continue = true; 

        while( $continue )
        {
            $this->createUser();

            $continue = $this->getYesNo('Create another user?');
        }

        $this->info('Done creating users.');
    }


    protected function createUser()
    {
        $email = $this->getEmail();

        if( \App\User::where('email', $email)->count() > 0 )
        {
            $this->error('User with that email already exists!');
        }
        else
        {
            $first = $this->getFirstName();

            $last = $this->ask('User last name?');

            $hash = $this->getPasswordHash();

            $isAdmin = $this->getYesNo( 'Admin user?' );

            $isSuper = $this->getYesNo( 'Super user?' );

            $data = [
                'email'      => $email, 
                'password'   => $hash, 
                'first_name' => $first,
                'last_name'  => $last, 
                'admin'      => $isAdmin ? 1 : 0,
                'super'      => $isSuper ? 1 : 0,
            ];

            $this->line('User data:');
            foreach( $data as $key => $value )
            {
                $value = (string) $value;
                $this->line("  $key:  $value ");
            }
            $this->line('');

            $user = \App\User::create($data);

            $this->info("User #{$user->id} created!");
        }
    }


    protected function getEmail()
    {
        $email = $this->ask('User email?');

        while( !$email )
        {
            $this->error('User email is required.');
           
            $email = $this->ask('User email name?');
        }

        return $email;
    }


    protected function getFirstName()
    {
        $first = $this->ask('User first name?');

        while( !$first )
        {
            $this->error('User first name is required.');
           
            $first = $this->ask('User first name?');
        }

        return $first;
    }


    protected function getPasswordHash()
    {
        $password = $this->secret('User password?');

        if( !$password )
        {
            $password = md5(mt_rand()) . md5(uniqid());
        }

        return \Illuminate\Support\Facades\Hash::make($password);
    }


    protected function getYesNo( $message )
    {
        $response = $this->ask( $message );

        return $response  &&  'y' == strtolower( substr($response, 0, 1) );
    }
}
