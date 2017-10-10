@extends('layouts.layout')

@section("content")
            <div class="doc-content">
        @if($content)
            {!! $content !!}
            <h3><a href="{{ url("/admin/document/edit/$stt") }}">*Chỉnh sửa bài viết*</a></h3>
        @else
            <h3><i>Tài liệu đang được cập nhật</i></h3>
        @endif
            </div>
@endsection
