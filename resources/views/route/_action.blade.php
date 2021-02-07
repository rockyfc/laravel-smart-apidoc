<div style="margin-bottom: 30px;">
    <p style="margin-top:30px;">
        <a><h4>{{$actionData['title']}}【{{$controller['title']}}】</h4></a>
        @if($actionData['deprecated']['isDeprecated'] or $actionData['controller']['deprecated']['isDeprecated'])
            <span class="label label-danger">已过期</span>
        @endif
    </p>

    @if($actionData['author'])
        <p>作者： {{implode(',',array_column($actionData['author'],'authorName'))}}</p>
    @endif

    @if($actionData['desc'])
        <p>说明：{{$actionData['desc']}}</p>
    @endif

    <p>
        <span class="label label-default">{{implode(' | ',$actionData['methods'])}}</span>
        {{$actionData['name']}}
    </p>


    @if(!empty($actionData['uriParams']))
        <h5>URL参数：</h5>
        @include('doc::route._input',['data'=>$actionData['uriParams'],'hasOptions'=>false])
    @endif


    <h5>输入参数：</h5>
    @include('doc::route._input',['data'=>@$actionData['request']['input'],'hasOptions'=>true])



    <h5>返回值：</h5>
    @include('doc::route._output',['data'=>@$actionData['response']['output']])


</div>
