    <h1> Reports </h1>
    <hr>
    <p class="mt-1 mb-4"> 
        <small class="text-muted"> Reports may take a while to generate depending on the number of entries, contestants, and users. </small> 
    </p>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ 'entries' == $active ? 'active' : '' }}" href="{{ route('L2.reports.entries', [$organization, $rodeo]) }}">
                Entries
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ 'draw' == $active ? 'active' : '' }}" href="{{ route('L2.reports.draw.days', [$organization, $rodeo]) }}">
                Draw Sheet
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ 'judge' == $active ? 'active' : '' }}" href="{{ route('L2.reports.judge.days', [$organization, $rodeo]) }}">
                Judge Sheet
            </a>
        </li>
    </ul>