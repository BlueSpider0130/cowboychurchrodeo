<?php

namespace App;

interface ScopeableStartEnd
{
    function scopeStarted($query);

    function scopeNotStarted($query);

    function scopeEnded($query);

    function scopeNotEnded($query);

    function scopeTBA($query);

    function scopeScheduled($query);

    function scopeInProgress($query);

}
