@extends('layouts.app')

@section('content')
    <div class="container">
        @if(count($events))
            <table class="table  table-hover">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Мероприятия</th>

                    <th scope="col">Город</th>
                </tr>
                </thead>
                <tbody>
                    @foreach( $events as $event)
                        <tr>
                            <th scope="row">{{ $event->id }}</th>
                           <td> <a href="{{ route('parser.show_event', $event->id) }}">{{ $event->title }}</a></td>

                            <td>{{ $event->city }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h3>Нет мероприятий</h3>
        @endif
            {{ $events->render() }}
    </div>

@endsection
