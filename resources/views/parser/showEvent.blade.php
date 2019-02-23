@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('parser.show_events') }}">Мероприятия</a></li>
                <li class="breadcrumb-item"><a href="#"> {{ $event->city }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ $event->title }}</a></li>
            </ol>
        </nav>
        <div class="alert alert-primary" role="alert">
            <h3 style="color: darkblue; font-family:'Trattatello, fantasy';">{{ $event->title }} </h3>
        </div>
        <div class="">
            <img src="{{ asset('/storage/'.$event->image) }}" class="rounded float-left" alt="...">
            {{ $event->description }}

        </div>

    </div>
@endsection
