<span>
    <a 
        href="#"
        role="button"
        onclick="if( confirm('{{ $message }}') ) { document.getElementById('delete-{{ $key }}').submit(); } else { return false; }"
        {{ $attributes->merge([]) }}
    >
        {{ $slot }}
    </a>
    <form id="delete-{{ $key }}" method="POST" action="{{ $url }}" style="display: none;">
        @method('DELETE')
        @csrf
    </form>
</span>