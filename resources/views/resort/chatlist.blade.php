<div class="chat-logo dropdown dropup ">
    <a href="#" id="chatDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-commenting" aria-hidden="true"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chatDropdown" style="width: 300px; right: 0; left: auto;">
        @foreach ($users as $index => $user)
    <li>
        <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('chat', $user->id) }}">

            @if ($user->messages_count > 0)
            <b>{{$user->name}}</b>
                <span class="badge bg-danger">{{ $user->messages_count }}</span>
            @else
            {{ $user->name }}
            @endif
        </a>
    </li>
    @if (!$loop->last)
        <hr class="dropdown-divider">
    @endif
@endforeach


    </ul>
</div>
