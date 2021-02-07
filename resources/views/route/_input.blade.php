<table class="table table-hover">
    <thead>
    <tr>
        <th>名称</th>
        <th>类型</th>
        <th >是否必须</th>
        @if(isset($hasOptions) and $hasOptions)
            <th width="25%">可选值</th>
        @endif
        <th>默认值</th>
        <th width="30%">描述</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($data))
        @foreach($data as $name=>$column)

            <tr>
                <td>{{$name}}</td>
                <td>{{$column['type']}}</td>
                <td>{!!$column['required']?'<spa  class="text-danger">是</span>':'否'!!}</td>

                @if(isset($hasOptions) and $hasOptions)
                    <td>
                        @if($column['isRelation']==true)
                            @php
                                $tmp = [];
                                foreach($column['options'] as $attribute=>$option){
                                    $tmp[] = $option = '<a href="'.route('doc.route.resources',['class'=>$option['type']]).'">'.$attribute.'</a>';
                                }
                            @endphp
                            {!! implode(', ',$tmp) !!}
                        @else
                            {{implode(', ',$column['options'])}}
                        @endif

                    </td>
                @endif
                <td>{{$column['default']}}</td>
                <td>{!! $column['comment'] !!}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" style="text-align: center"><span class="text-muted">没有参数！</span></td>
        </tr>
    @endif
    </tbody>
</table>
