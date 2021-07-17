@extends('doc::route.layout')

@section('content')

    <div class="page-header">

        <h2>
            {{$data['title']}}
            <small> {{$data['name']}} </small>
        </h2>

        <p>
            {{$data['desc']}}
        </p>
    </div>
    {{--<div id="{{md5($data['name'])}}" style="height:60px;margin-top: -60px;"></div>
    <div style="margin-bottom: 30px;"></div>--}}

    <h5>参数：</h5>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th>类型</th>
            <th>可选值</th>
            <th width="30%">描述</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['fields'] as $name=>$column)

            <tr>
                <td>{{$name}}</td>
                <td>
                    @if(class_exists($column['type']) and $column['type'] instanceof Illuminate\Http\Resources\Json\JsonResource)
                        <a href="{{route('doc.route.resources',['class'=>$column['type']])}}">{{$column['type']}}</a>
                    @else
                        {{$column['type']}}
                    @endif
                </td>
                <td>{{implode(',',$column['options'])}}</td>
                <td>{!! $column['comment'] !!}</td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <h5>关联对象：</h5>
    @foreach($data['relationsFields'] as $attribute=>$column)
        <a href="{{route('doc.route.resources',['class'=>$column['type']])}}"><strong>{{$attribute}}</strong></a>：{{$column['comment']}}<br/>
    @endforeach

    <p class="text-right">
        @by {{implode(',',array_column($data['author'],'authorName'))}}
    </p>


@endsection
