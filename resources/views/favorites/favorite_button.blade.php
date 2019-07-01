@if (Auth::id() != $micropost->user_id)
    @if (Auth::user()->is_favorite($micropost->id))
        {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfavorite', ['class' => "btn btn-success  btn-sm d-inline-block m-1"]) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
            {!! Form::submit('Favorite', ['class' => "btn btn-primary  btn-sm d-inline-block m-1"]) !!}
        {!! Form::close() !!}
    @endif
@endif
