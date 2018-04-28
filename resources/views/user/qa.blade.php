@extends('layouts.layout')

@section('title')
    Sổ tay 56 - Hỏi đáp
@endsection

@section('content')
            <div class="feedback-header"><h2>Hỏi Đáp</h2></div>
            <p>Quý khách thân mến !</p>
            <br/>
            <p>Để thuận tiện trong việc gửi và nhận thông tin, quý khách vui lòng điền chính xác thông tin vào các ô phía dưới và sử dụng tiếng Việt có dấu để viết nội dung.</p>
            <br/>
            <p>Sau khi nhận được câu hỏi, chúng tôi sẽ khẩn trương nghiên cứu và trả lời, nhất là những câu hỏi có nhiều người quan tâm.</p>
            <br/>
            <p>Nội dung trả lời sẽ được đăng tải trên mục HỎI ĐÁP. Đối với câu hỏi của Thành viên, nội dung trả lời sẽ đồng thời được gửi đến địa chỉ email đã đăng ký thành viên.</p>
            <br/>
        @if(isset($isQA))
            <div class="feedback-done">
                <img class="" src="{{ url('public/assets/images/accept.png') }}"/>
                <i>Câu hỏi của bạn đã được gửi đi.</i>
            </div>
            <script type="text/javascript">
                $('.feedback-done').fadeIn('slow');
            </script>
        @else
            {{ Form::open(['url' => url('hoidap'),'id' => 'qa-form','method'=>'post','class' => 'qa-form']) }}
            @if(!Auth::user())
                <div class='qa-row clear'>
                    <div class='qa-col qa-col-l'>Email</div>
                    <div class='qa-col qa-col-r'>
                        {{ Form::text('email', '', ['class' => 'qa-input']) }}
                        @if($errors->has('email'))
                            <p class='error'>{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>
            @endif
                <div class='qa-row clear'>
                    <div class='qa-col qa-col-l'>Chuyên mục</div>
                    <div class='qa-col qa-col-r'>
                        {{ Form::select('category', [null=>'-- Chuyên mục --'] + $cats, '', ['class' => 'qa-select']) }}
                    </div>
                </div>
                <div class='qa-row clear'>
                    <div class='qa-col qa-col-l'>Tiêu đề*</div>
                    <div class='qa-col qa-col-r'>
                        {{ Form::text('title', '', ['class' => 'qa-input']) }}
                        @if($errors->has('title'))
                            <p class='error'>{{ $errors->first('title') }}</p>
                        @endif
                    </div>
                </div>
                <div class='qa-row clear'>
                    <div class='qa-col qa-col-l'>Nội dung*</div>
                    <div class='qa-col qa-col-r'>
                        {{ Form::textarea('question', '', ['class' => 'qa-textarea', 'rows' => '20']) }}
                        @if($errors->has('question'))
                            <p class='error'>{{ $errors->first('question') }}</p>
                        @endif
                    </div>
                </div>
                <div>
                    {{ Form::submit('Gửi', ['class' => 'reg-body-button']) }}
                </div>
            {{ Form::close() }}
        @endif
@endsection  