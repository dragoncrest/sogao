@extends('layouts.layout')

@section('title')
    Góp ý
@endsection

@section("content")
            <div class="feedback-header"><h2>GÓP Ý</h2></div>
        @if(isset($isFeedback))
            <div class="feedback-done">
                <i>Ý kiến đóng góp của bạn đã được gửi đi.</i><br/>
                <i>Chúng tôi sẽ hoàn thiện hơn nữa để đáp ứng nhu cầu người sử dụng.</i>
            </div>
        @else
            <div class="feedback-body">
                {{ Form::open(['url' => url('feedback'),'id' => 'myform','method'=>'post','class' => 'feedback-form']) }}
                <form id="sear-i" class="sear-form clear" method="post" onsubmit="return $.CheckSearch();"  action="/search">
                    <div class="feedback-row-1">
                        {{ Form::text('title', '', ['class' => 'fb-ip-title', 'placeholder' => 'Tên bài viết']) }}
                        <select type="text" class="fb-ip-title" name="category">
                            <option value=''>-- Chuyên mục --</option>
                            @foreach($cates as $cat)
                            <option value="{!! $cat->title !!}">{!! $cat->title !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="feedback-row-2 {{ $errors->has('content') ? 'error' : '' }}">
                        {{ Form::textarea('content', '', ['class' => 'fb-txtarea', 'placeholder' => 'Nội dung', 'rows' => '20']) }}
                        @if($errors->has('content'))
                            <p>{{ $errors->first('content') }}</p>
                        @endif
                    </div>
                    {{ Form::submit('Gửi', ['class' => 'reg-body-button']) }}
                {{ Form::close() }}
                </form>
            </div>
        @endif
@endsection