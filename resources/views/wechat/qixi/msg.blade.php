@extends('wechat.qixi.component')

@section('content')
    @if (isset($msg))
        <h2 style="width:100%;text-align:center;margin-top:100px">{{$msg}}&nbsp;|&nbsp;404&nbsp;Not&nbsp;Found</h2>
    @else
        @parent
    @endif
@endsection