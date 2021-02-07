@extends('doc::route.layout')

@section('content')
    @foreach($controllers as $comment)
        <p>
            <a href="{{route('doc.route.actions',['name'=>$comment['controller']])}}">
                地址：{{$comment['controller']}}
                名称：{{$comment['title']}}
            </a>
        </p>
    @endforeach
@endsection


