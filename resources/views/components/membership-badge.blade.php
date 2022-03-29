<span {{ $attributes }}>
    @if( $seriesId )
        @if( $membership )
            @if( !$membership->paid  ||  $membership->pending )
                <span class="pending-member-badge"> PENDING MEMBER </span>
            @else
                <span class="member-badge"> MEMBER </span>
            @endif
        @elseif( $showNonMember )
            <span class="non-member-badge"> NON-MEMBER </span>
        @endif
    @endif
</span>