<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamRopingEntry extends Model
{
    protected $fillable = [
        'header_entry_id',
        'heeler_entry_id',
        'instance_id',
    ];

    public function header_entry()
    {
        return $this->belongsTo( CompetitionEntry::class, 'header_entry_id' );
    }

    public function heeler_entry()
    {
        return $this->belongsTo( CompetitionEntry::class, 'heeler_entry_id' );
    }

    public function header()
    {
        return $this->header_entry->contestant();
    }

    public function heeler()
    {
        return $this->heeler_entry->contestant();
    }

    public function instance()
    {
        return $this->belongsTo( CompetitionInstance::class, 'instance_id' );
    }


    public function scopeForEntry($query, $entryId)
    {
        return $query->where('header_entry_id', $entryId)
                ->orWhere('heeler_entry_id', $entryId);
    }    

    public function scopeForHeaderEntry($query, $entryId)
    {
        return $query->where('header_entry_id', $entryId);
    }

    public function scopeForHeelerEntry($query, $entryId)
    {
        return $query->where('heeler_entry_id', $entryId);
    }

    public function scopeForCompetition($query, $competitionId)
    {
        $entryIds = CompetitionEntry::where('competition_id', $competitionId)->pluck('id')->toArray();

        return $query->whereIn('header_entry_id', $entryIds)
                ->orWhereIn('heeler_entry_id', $entryIds);
    }
}
