@extends('doc::route.layout')

@section('content')
    <div class="page-header">
        @include('doc::route._message')
        <form method="GET" action="{{route('doc.route.filter')}}">
            <div class="form-group">
                <div class="col-sm-8">
                    <label for="keyword" class="sr-only">关键字</label>
                    <input type="text" class="form-control" name="keyword" id="keyword"
                           value="{{request()->input('keyword')}}" placeholder="输入关键字，标题、路由地址、作者、Controller、Action等">
                </div>

                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary">
                        搜索
                    </button>
                    &nbsp;&nbsp;
                    <span class="text-right" style="color:gray;">共有{{count($actions)}}个路由。</span>
                </div>

            </div>


        </form>
        <p>&nbsp;</p>
    </div>
{{--
    <p class="text-right">共有{{count($actions)}}个路由。</p>
--}}
    <table class="table table-hover ">
        <thead>
        <tr>
            <th>#</th>
            <th>标题</th>
            <th>地址</th>
            <th>作者</th>
            <th>添加时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($actions as $k=>$action)
            <tr class="dotted-line">
                <td>{{++$k}}</td>
                <td>
                    @if($action['deprecated']['isDeprecated'] or $action['controller']['deprecated']['isDeprecated'])
                        <span class="label label-danger">已过期</span>
                    @endif
                    {{$action['controller']['title']}} / {{$action['title']}}
                </td>
                <td>
                    {{$action['name']}}
                    <br/>
                    <span class="label label-default">{{implode(' | ',$action['methods'])}}</span>
                </td>


                <td>{{!empty($a = implode(',',array_column($action['author'],'authorName')))?$a:'- -'}}</td>
                <td>
                    {!! !empty($a = str_ireplace(' ','<br/>',$action['created_at']))?$a:'- -' !!}
                </td>
                <td>
                    <a href="{{route('doc.route.view',['name'=>$action['action']])}}">详情</a>
                    {{--
                                            <a href="{{route('route.actions',['name'=>$action['controller']['controller']])}}">相关</a>
                    --}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
