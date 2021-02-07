@if(session()->has('_errors'))
    <div class="alert alert-danger" role="alert">
{{--
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"><h4>警告</h4></span>
--}}
        {{--@if($controller['deprecated']['isDeprecated'])
            @if($controller['deprecated']['desc'])
                {{implode('，',$controller['deprecated']['desc'])}}
            @else
                <p><strong>该系列接口已弃用！</strong></p>
            @endif
        @endif--}}

        <strong>警告：</strong>
        @if(session()->has('_errors'))
            @foreach(session('_errors') as $k=>$error)
                <p>{{$loop->index+1}}. {{$error}}</p>
            @endforeach
        @endif

    </div>
@endif
