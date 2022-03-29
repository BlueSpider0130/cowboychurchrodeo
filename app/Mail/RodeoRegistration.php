<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RodeoRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $rodeoEntry;
    public $rodeo;
    public $contestant;
    public $competitionEntries;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( \App\RodeoEntry $rodeoEntry )
    {
        $rodeoEntry->load([
            'contestant', 
            'rodeo', 
            'rodeo.competition_entries', 
            'rodeo.competition_entries.competition', 
            'rodeo.competition_entries.competition.event',
            'rodeo.competition_entries.competition.group'
        ]);

        $this->rodeoEntry = $rodeoEntry;
        $this->rodeo = $rodeoEntry->rodeo;
        $this->contestant = $rodeoEntry->contestant;
        $this->competitionEntries = $rodeoEntry
                                        ->rodeo
                                        ->competition_entries
                                        ->where('contestant_id', $this->contestant->id)
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.rodeo_registration');
        // return $this->from('example@example.com')
        //         ->view('emails.orders.shipped');        
    }
}
