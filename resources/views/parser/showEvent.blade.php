@extends('layouts.app')

@section('content')
    @if($event)
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
        <div class="row">
            <div class="col-12 col-md-4">
                <img src="{{ asset('/storage/images/'.$event->image) }}" class="rounded float-left" alt="..."
                     width="300" style="margin-right: 20px;" >
            </div>
            <div class="col-12 col-md-8">
                {!! $event->description !!}

                <div style="display: block; ">
                    @foreach($sessions as $date => $session)
                        @foreach($session as $times)
                            @foreach($times as $time)
                                <div style="display: flex; flex-direction: column">
                                    <div  style=" display: flex; justify-content: start; margin-bottom: 10px">
                                        <div class="btn btn-primary" style="margin-right: 10px; ">
                                            <span>{{ $date }}</span>
                                        </div>
                                        <div class="btn btn-success" >
                                            <span>{{$time}}</span>
                                        </div>

                                    </div>
                                </div>

                            @endforeach
                        @endforeach
                    @endforeach
                </div>
                <div style="display: block">
                    <a href="{{ $event->link }}" target="_blank">Посмотреть на сайте</a>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
