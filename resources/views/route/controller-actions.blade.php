@extends('doc::route.layout')

@section('content')
    <div class="col-md-2">
        <div style="position: fixed;">
            @foreach($actions as $comment)
                <p>
                    <a href="#{{md5($comment['action'])}}">{{$comment['title']}}</a>
                    {{--<a href="{{route('route.actions',['name'=>$comment['title']])}}">
                        {{$comment['name']}}
                    </a>--}}
                </p>
            @endforeach
        </div>
    </div>


    <div class="col-md-10">
        <div class="page-header">
            <h2>
                {{$controller['title']}}
                <small> {{$controller['controller']}} </small>
            </h2>
            @include('doc::route._message')
            <p> {{$controller['desc']}} </p>
        </div>
        @foreach($actions as $comment)
            <div id="{{md5($comment['action'])}}" style="height:60px;margin-top: -60px;"></div>
            @include('doc::route._action',['actionData'=>$comment,'controller'=>$controller])
        @endforeach
    </div>
@endsection
