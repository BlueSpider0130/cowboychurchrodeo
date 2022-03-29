<div class="dropdown">
    <a 
        id="dropdown-menu-link-{{ $key }}" 
        class="text-secondary" 
        href="#" 
        role="button"         
        data-toggle="dropdown" 
        aria-haspopup="true" 
        aria-expanded="false"
    >
        @if( isset($icon)  &&  $icon )
            {{ $icon }}
        @else
            <i class="fas fa-ellipsis-h"></i>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-menu-link-{{ $key }}">
        
        

        @if( $editUrl )
            <a class="dropdown-item" href="{{ $editUrl }}">Edit</a>
        @endif

        @if( $deleteUrl )
            <a 
                class="dropdown-item text-danger" 
                href="#" 
                onclick="confirmDelete('delete-{{ $key }}', '{{ $deleteMessage }}')"
            >
                Delete
            </a>
            <form id="delete-{{ $key }}" method="POST" action="{{ $deleteUrl }}" style="display: none;">
                @method('DELETE')
                @csrf
            </form>
        @endif 
    </div>
</div>
