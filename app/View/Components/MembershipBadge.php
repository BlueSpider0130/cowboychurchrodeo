<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Contestant;
use App\Series;

class MembershipBadge extends Component
{
    public $seriesId;
    public $membership; 
    public $showNonMember;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( Contestant $contestant, $series = null, $showNonMember = false )
    {
        $this->seriesId = null !== $series  &&  is_a($series, Series::class)  ?  $series->id  :  $series;
        $this->membership = $this->seriesId  ?  $contestant->memberships->where('series_id', $this->seriesId)->first()  :  null;
        $this->showNonMember = $showNonMember;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.membership-badge');
    }
}
