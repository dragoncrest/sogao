@extends('layouts.layout')

@section('header')
    <script type="text/javascript" src="{{ url('public/assets/js/document.js') }}"></script>
@endsection

@section('title')
    {!! $title ? $title : 'Sổ tay 56' !!}
@endsection

@section('content')
            <?php echo view('templates/search_form', ['currentCat' => $currentCat]);?>

            <div class="doc-content">
        @if(isset($status))
            @include('templates.example')
        @elseif($content)
            @if($currentCat->isDownload)
                <h3><a href="javascript:void(0)" id="{!! $id !!}" class="downloadLink">Tải văn bản</a></h3>
            @endif
            {!! $content !!}

            @if(Auth::user() && (Auth::user()->role_id == ROLE_ADMIN))
                <h3><a href="{{ url("/admin/document/edit/$stt") }}">*Chỉnh sửa bài viết*</a></h3>
            @endif
        @else
            <h3><i>Tài liệu đang được cập nhật</i></h3>
        @endif
            </div>

            <div id="dialog" class="popup-dialog">
                <div class="pop-close pop-close-but"></div>
                <div class="pop-content"></div>
            </div>

            <div id="minus-coin" class="minus-coin">-1   <img src="{{ url('public/assets/images/coin.gif') }}"/></div>

            <script id="dialogStatus-template" type="text/x-custom-template">
                <div class="_content"><%= _str %></div>
                <% if ((typeof(_idDoc) != 'undefined') && _idDoc) { %>
                <div class="_button">
                    <a href="javascript:void(0)" class="pop-but pop-but-close" onclick="hidePopupDialog('#dialog');">Hủy</a>
                    <a href="javascript:void(0)" class="pop-but pop-but-buy" onclick="buyDocument('<%= _idDoc %>')">Đồng ý</a>
                </div>
                <% } %>
                <% if (typeof(_isBuying) != 'undefined') { %>
                <div class="pop-loading">
                    <img src="{{ url('public/assets/images/loading-buy.gif') }}" />
                </div>
                <% } %>
            </script>

            <script type="text/javascript">
                var BUYED   = '{!! BUYED !!}';
                var NOTCOIN = '{!! NOTCOIN !!}';
                var LOGIN   = '{!! LOGIN !!}';
                var BUY     = '{!! BUY !!}';

                var urlDocument = "{!! url('/document/') !!}/";
                var urlHowToBuy = "{!! url('/phi-dich-vu/') !!}/";
                var urlDownload = "{!! url('/document/download/'.$id) !!}";
                var urlAjaxBuyDocument    = "{!! url('/document/ajaxBuyDocument/') !!}/";
                var urlAjaxCheckFileExits = "{!! url('/document/ajaxCheckFileExits/'.$id) !!}";

                var coin   = {!! $coin !!};
                var status = '';
                var sttDoc = '{!! $stt !!}';
                @if(isset($status))
                    status = '{!! $status !!}';
                @endif

            @if($currentCat && $currentCat->isDownload)
                $('.downloadLink').on("click", function() {
                    id = "{!! $id ? $id : 0 !!}";
                    if (!id) {
                        alert( "Mã không tồn tại" );
                        return;
                    }
                    $.ajax({
                        url: urlAjaxCheckFileExits,
                        type: "GET",
                        cache: false,
                    }).done(function( result ) {
                        if (result.status) {
                            window.location.href = urlDownload;
                        } else {
                            alert(result.message);
                        }
                    }).fail(function( jqXHR, textStatus ) {
                        alert( "Request failed: " + textStatus );
                    });
                });
            @endif
            </script>
@endsection