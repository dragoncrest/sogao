@extends('layouts.layout')

@section("content")
            <div class="doc-content">
        @if($content)
            {!! $content !!}
            <h3>Tải văn bản <a href="javascript:void(0)" id="{!! $id !!}" class="downloadLink">{!! $title !!}</a></h3>
            <h3><a href="{{ url("/admin/document/edit/$stt") }}">*Chỉnh sửa bài viết*</a></h3>
        @else
            <h3><i>Tài liệu đang được cập nhật</i></h3>
        @endif
            </div>

            <script type="text/javascript">
                $('.downloadLink').on("click", function() {
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

                    // $.ajax({
                    //     type: "GET",
                    //     url: "{!! url('/document/ajaxDownload/'.$id) !!}",
                    //     dataType: 'JSON',
                    //     cache: false,
                    //     success: function (data) {
                    //         console.log(data);
                    //     },
                    //     error: function (data) {
                    //         console.log('Error:', data);
                    //     }
                    // });
                });
            </script>
@endsection