@extends('layouts.layout')

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
