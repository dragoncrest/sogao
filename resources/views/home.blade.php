@extends('layouts.layout')

@section('title')
    Trang chủ - Sổ tay 56
@endsection

@section('content')
            <script type="text/javascript">
                $(document).ready(function() {
                   // $(".fancybox").fancybox();
                });
            </script>
            <div id="content">
                @if($content)
                    {!! $content !!}
                @endif
            </div>
@endsection
