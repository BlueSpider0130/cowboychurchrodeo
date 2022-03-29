<span> 
    Operating as &nbsp; <u>{{ $user->getName() }}</u> &nbsp; 
    <a href="{{ route('admin.user.operator.end') }}" style="font-size: .75rem"> 
        return to {{ $operator ? $operator->getName() : 'self' }} </a> 
</span>