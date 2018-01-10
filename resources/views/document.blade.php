@extends('layouts.layout')

@section("content")
            <div class="doc-content">
        @if(isset($status))
            <h2>Bạn không được xem tài liệu này!</h2>
        @elseif($content)
            @if($currentCat->isDownload)
                <h3><a href="javascript:void(0)" id="{!! $id !!}" class="downloadLink">Tải văn bản</a></h3>
            @endif
            {!! $content !!}
            <h3><a href="{{ url("/admin/document/edit/$stt") }}">*Chỉnh sửa bài viết*</a></h3>
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
                    <a href="javascript:void(0)" class="pop-but pop-but-close" onclick="hidePopupDialg('#dialog');">Đóng</a>
                    <a href="javascript:void(0)" class="pop-but pop-but-buy" onclick="buyDocument('<%= _idDoc %>')">Mở</a>
                </div>
                <% } %>
                <% if (typeof(_isBuying) != 'undefined') { %>
                <div class="pop-loading">
                    <img src="{{ url('public/assets/images/loading-buy.gif') }}" />
                </div>
                <% } %>
            </script>
            <script type="text/javascript">
                function checkUserStatus(idDoc, status)
                {
                    if (status == '{!! BUYED !!}') {
                        window.open('{!! url('/document/') !!}' + "/" + idDoc, '_blank');
                    } else if (status == '{!! NOTCOIN !!}') {
                        str   = "Số xu của bạn không đủ để mở văn bản này. ";
                        str  += "Click vào <a href=''>đây</a> để nạp thêm";
                        idDoc = 0;
                    } else if (status == '{!! LOGIN !!}') {
                        str   = "Bạn phải đăng nhập và là thành viên VIP";
                        str  += " mới có thể xem bài viết này.";
                        idDoc = 0;
                    } else if (status == '{!! BUY !!}') {
                        str  = "Mất 1 xu để xem bài viết này";
                    }
                    if (status != '{!! BUYED !!}') {
                        var template = $('#dialogStatus-template').html();
                        var compiled = _.template(template);
                        $('#dialog .pop-content').html(compiled({_str: str, _idDoc: idDoc}));
                        showPopupDialg('#dialog');
                    }
                }
                $('.pop-close-but').on("click", function() {
                    hidePopupDialg('#dialog');
                });
                function buyDocument(id)
                {
                    sessionStorage.scrollTop = $(window).scrollTop();
                    var str      = "Đang xử lý";
                    var template = $('#dialogStatus-template').html();
                    var compiled = _.template(template);
                    $('#dialog .pop-content').html(compiled({_str: str, _isBuying: true}));

                    $.ajax({
                        type: "GET",
                        url: "{!! url('/document/ajaxBuyDocument/') !!}/" + id,
                        dataType: 'JSON',
                        cache: false,
                        async: false,
                        success: function (data) {
                            if (data.status) {
                                sessionStorage.buy = 1;
                                location.reload();
                            } else {
                                hidePopupDialg('#dialog');
                            }
                        },
                        error: function (data) {
                            hidePopupDialg('#dialog');
                            console.log('Error:', data);
                        }
                    });
                }
                $(document).ready(function() {
                    @if(isset($status))
                        var status = '{!! $status !!}';
                        checkUserStatus('{!! $stt !!}', '{!! $status !!}');
                    @endif
                    var buy    = parseInt(sessionStorage.buy);
                    var scroll = parseInt(sessionStorage.scrollTop);
                    if (!isNaN(buy)) {
                        $("#minus-coin").show().animate({top: '5%'});
                        setTimeout(function() {
                            $("#minus-coin").fadeOut();
                        }, 1500);
                        sessionStorage.removeItem("buy");
                    }
                    if (!isNaN(scroll)) {
                        $(window).scrollTop(scroll);
                        sessionStorage.removeItem("scrollTop");
                    }
                });
            @if($currentCat->isDownload)
                $('.downloadLink').on("click", function() {
                    id = {!! $id ? $id : 0 !!};
                    if (!id) {
                        alert( "Mã không tồn tại" );
                        return;
                    }
                    $.ajax({
                        url: "{!! url('/document/ajaxCheckFileExits/'.$id) !!}",
                        type: "GET",
                        cache: false,
                    }).done(function( result ) {
                        if (result.status) {
                            window.location.href = "{!! url('/document/download/'.$id) !!}";
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