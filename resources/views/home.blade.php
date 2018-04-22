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

            <?php echo view('templates/search_form', ['currentCat' => $currentCat]);?>

            <div id="content">
                @if($content)
                    {!! $content !!}
                @endif
            </div>
@endsection
