@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table  table-hover">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Мероприятия</th>
                <th scope="col">Дата</th>
                <th scope="col">Город</th>
            </tr>
            </thead>
            <tbody>
            @if($events)
            @foreach( $events as $event)
            <tr>
                <th scope="row">{{ $event->id }}</th>
               <td> <a href="{{ route('parser.show_event', $event->id) }}">{{ $event->title }}</a></td>
                <td>{{ $event->start_date }}</td>
                <td>{{ $event->city }}</td>
            </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
