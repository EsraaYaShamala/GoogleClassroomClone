<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        Notifications
        @if ($unreadCount)
            <span class="badge bg-primary">{{ $unreadCount }}</span>
        @endif
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        @foreach ($notifications as $notification)
            <li><a class="dropdown-item" href="{{ $notification->data['link'] }}?nid={{ $notification->id }}">
                    @if ($notification->unread())
                        <b>{{ $notification->data['body'] }}</b>
                    @else
                        {{ $notification->data['body'] }}
                    @endif
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
        @endforeach
    </ul>
</li>
