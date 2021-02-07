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
    @if($data)
        @foreach($data as $name=>$column)

            <tr>
                <td>{{$name}}</td>
                <td>{{$column['type']}}</td>
                <td>{{implode(',',$column['options'])}}</td>
                <td>{!! $column['comment'] !!}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4" style="text-align: center"><span class="text-muted">没有参数！</span></td>
        </tr>
    @endif
    </tbody>
</table>
