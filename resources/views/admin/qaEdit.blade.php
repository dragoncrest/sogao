@extends('admin.layout')
 
@section('content')
            <div class="content">
                
                <div class="page-header">
                    <div class="icon">
                        <span class="ico-layout-7"></span>
                    </div>
                    <h1>Chỉnh sửa hỏi đáp<small>METRO STYLE ADMIN PANEL</small></h1>
                </div>
                
                <div class="row-fluid">
                @if(isset($data['isEditted']))
                    <div class="row-form editted">
                        <span class='span2'></span>
                        <div class="span10 alert-1 alert-success-1">
                            <img class="" src="{{ url('public/assets/images/accept.png') }}"/>
                            <strong>Cập nhật thành công!</strong>
                       </div>
                    </div>
                    <script type="text/javascript">
                        $( document ).ready(function() {
                            setTimeout(function() {
                                $('.editted').slideUp(1000);
                            }, 3000);
                        });
                    </script>
                @endif
                    {{ Form::open(['url' => url('admin/qa/'.$data['qa']['id']),'id' => 'qaForm','method'=>'post']) }}
                        <div class="row-form {{ $errors->has('email') ? 'error' : '' }}">
                            <span class='span2'>Email:</span>
                            <div class='span5'>
                                {{ Form::text('email', $data['qa']['email'], ['disabled']) }}
                                @if ($errors->has('email'))
                                    <strong>{{ $errors->first('email') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row-form">
                            <span class='span2'>Chuyên mục:</span>
                            <div class='span5'>
                                {{ Form::select('category', [null=>'-- Chuyên mục --'] + $data['cats'], $data['qa']['category_id']) }}
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('title') ? 'error' : '' }}">
                            <span class='span2'>Tiêu đề:</span>
                            <div class='span5'>
                                {{ Form::text('title', $data['qa']['title']) }}
                                @if ($errors->has('title'))
                                    <span>
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('question') ? 'error' : '' }}">
                            <span class='span2'>Câu hỏi:</span>
                            <div class='span10'>
                                {{ Form::textarea('question', $data['qa']['question']) }}
                                @if ($errors->has('question')) {
                                    <span>
                                        <strong>{{ $errors->first('question') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('answer') ? 'error' : '' }}">
                            <span class='span2'>Trả lời:</span>
                            <div class='span10'>
                                {{ Form::textarea('answer', $data['qa']['answer'], ['class' => 'qa-textarea']) }}
                                @if ($errors->has('answer'))
                                    <span>
                                        <strong>{{ $errors->first('answer') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div style="width:100%; text-align:center;">
                            <input type="submit" value="Cập nhật!" class="btn" />
                        </div>
                    {{ Form::close() }}
                </div><!-- end row-fluid -->
            </div><!-- end content -->
@endsection