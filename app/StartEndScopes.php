<?php

namespace App;


trait StartEndScopes 
{
    /**
     * Scope query to those that have started.
     */
    public function scopeStarted($query)
    {
        return $query->where( 'starts_at', '<=', \Carbon\Carbon::now() );
    }


    /**
     * Scope query to those that have NOT started.
     */
    public function scopeNotStarted($query)
    {
        return $query->where( 'starts_at', '>', \Carbon\Carbon::now() );
    }


    /**
     * Scope query to those that have ended.
     */
    public function scopeEnded($query)
    {       
        return $query
                ->where( function($q2) {
                    return $q2
                            ->whereNotNull( 'ends_at' )
                            ->where( 'ends_at', '<=', \Carbon\Carbon::now()->endOfDay() );
                });
    }    


    /**
     * Scope query to those that have NOT ended.
     */
    public function scopeNotEnded($query)
    {
        return $query
                ->where( function($q) {
                    return $q
                            ->where( function($q2) {
                                return $q2
                                        ->whereNotNull( 'ends_at' )
                                        ->where( 'ends_at', '>=', \Carbon\Carbon::now()->startOfDay() );
                            })
                            ->orWhere( function($q2) {
                                return $q2
                                        ->whereNull( 'ends_at' )
                                        ->whereNotNull( 'starts_at' )
                                        ->where( 'starts_at', '>=', \Carbon\Carbon::now()->startOfDay() );
                            });
                });
    }


    /**
     * Scope query to those that do not have a start date/time.
     */
    public function scopeTBA($query)
    {
        return $query->whereNull( 'starts_at' );
    }


    /**
     * Scope query to those that have start time in the future.
     */
    public function scopeScheduled($query)
    {
        return $query
                ->where( function($q) {
                    return $q
                            ->whereNotNull( 'starts_at' )
                            ->where( 'starts_at', '>', \Carbon\Carbon::now() );
                }); 
    }


    /**
     * Scope query to those that have started but not ended yet.
     */
    public function scopeInProgress($query)
    {
        return $query
                ->where( function($q) {
                    return $q->started()->notEnded();
                });        
    }

}
