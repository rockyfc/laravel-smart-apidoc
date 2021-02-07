@extends('doc::route.layout')

@section('content')
    {{--<div class="jumbotron">
        <h2>{{$action['controller']['title']}} </h2>
        <p>{{$action['controller']['desc']}} </p>
    </div>--}}
    <div class="page-header">
        <h3>
            {{$action['title']}}【{{$action['controller']['title']}}】
            <small> {{$action['action']}} </small>
        </h3>
        <p> {{$action['controller']['desc']}} </p>
        <p>{{$action['desc']}}</p>
    </div>

    <div >
        <p>切换到  <a href="{{route('doc.route.markdown',['name'=>$action['action']])}}" class="btn-default btn-link " >Markdown</a> 语法</p>

        <p>
            @if($action['deprecated']['isDeprecated'] or $action['controller']['deprecated']['isDeprecated'])
                <span class="label label-danger">已过期</span>
            @endif
        </p>

        @if($action['author'])
            <p>作者： {{implode(',',array_column($action['author'],'authorName'))}}
                <a href="mailto:{{implode(',',array_column($action['author'],'email'))}}?subject=【{{$action['title'].' '.$action['controller']['title']}}】接口建议&cc=softfc@163.com&body=">
                    {{implode(',',array_column($action['author'],'email'))}}
                </a>
            </p>
        @endif

        @if($action['created_at'])
            <p>添加日期： {{$action['created_at']}}</p>
        @endif

        <p>
            地址：<span class="label label-default">{{implode(' | ',$action['methods'])}}</span>
            {{$action['name']}}
        </p>
        <h5>公共输入参数：<small>同URL请求参数一样，放到url上发送。</small></h5>
        @include('doc::route._input',['data'=>$action['commonRequest'],'hasOptions'=>false])


        @if(!empty($action['uriParams']))
            <h5>URL参数：<small>Http Query String Parameters</small></h5>
            @include('doc::route._input',['data'=>$action['uriParams'],'hasOptions'=>false])
        @endif


        <h5>输入参数：<small>Http Body Data </small></h5>
        @include('doc::route._input',['data'=>@$action['request']['input'],'hasOptions'=>true])


        <h5>返回值：</h5>
        @include('doc::route._output',['data'=>@$action['response']['output']])



        <script>hljs.initHighlightingOnLoad();</script>

        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#php-demo" data-toggle="tab">PHP调用示例</a></li>
            <li role="presentation"><a href="#js-demo" data-toggle="tab">JS调用示例</a></li>
        </ul>

        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active " style="padding:10px;border-bottom:1px solid #ddd;border-right:1px solid #ddd;border-left:1px solid #ddd;" id="php-demo">
                <p>
                    <pre style="background: #fff;border: 0;">
                    <code class="lang-php7" style="background: #fff;border: 0;">{{$action['sdk']['php']}}</code>
                </pre>
                </p>

            </div>
            <div class="tab-pane fade" id="js-demo">
                <p>JS...</p>
            </div>


        </div>
    </div>

@endsection
