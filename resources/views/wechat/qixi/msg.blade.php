@extends('wechat.qixi.component')

@section('content')
    @if (isset($msg))
        <h3 style="width:100%;text-align:center;margin-top:40vh;margin-bottom:40vh;">{{$msg}}
            <br>
            <a  onclick="back()"
                href="javascript:void(0)" >点我返回</a></h3>
    @else
        @parent
    @endif
@endsection

@section("js")
    <script>
        var back = function () {
            window.history.back();
        }
    </script>
@endsection
