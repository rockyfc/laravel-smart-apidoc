<div class="fixed">
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#home" data-toggle="tab">模块</a></li>
        <li role="presentation"><a href="#ios" data-toggle="tab">约定</a></li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" style="padding:10px;" id="home">

            <p><a href="{{route('doc.route.filter')}}">全部</a></p>
            {{--@foreach($controllers as $comment)
                <p>
                    <a title="{{$comment['controller']}}"
                       href="{{route('doc.route.filter',['keyword'=>$comment['controller']])}}">
                        {{$comment['title']}}
                    </a>
                </p>
            @endforeach--}}
            <style>
                .list-unstyled ul {
                    display: block;
                }
            </style>

            <ul class="list-unstyled">
                @foreach($menus as $module)
                    <li><a>{{$module['name']}}</a>
                        <ul>
                            @foreach($module['routes'] as $ctrlName=>$routeName)
                                <li>
                                    <a title="{{$controllers[$ctrlName]['controller']}}"
                                       href="{{route('doc.route.filter',['keyword'=>$controllers[$ctrlName]['title'],'module'=>$module['uriPrefix']])}}">
                                        {{$controllers[$ctrlName]['title']}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>


        </div>
        <div class="tab-pane fade" style="padding:10px;" id="ios">

            @foreach($files as $file)
                <p><a href="{{route('doc.route.file',['file'=>$file['key']])}}">{{$file['name']}}</a></p>
            @endforeach
        </div>


    </div>


</div>
