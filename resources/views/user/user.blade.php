@extends('layouts.layout')

@section('title')
    Thông tin cá nhân
@endsection

@section("content")
            <div class="reg-header"><h2>thông tin cá nhân</h2></div>
            

            @if(isset($updated))
            <div class="alert-success">
                <img class="" src="{{ url('public/assets/images/accept.png') }}"/>
                {{ $updated }}
            </div>
            <script type="text/javascript">
                $( document ).ready(function() {
                    setTimeout(function() {
                        $('.alert-success').slideUp(1000);
                    }, 3000);
                });
            </script>
            @endif
            
            <div class="reg-body">
                <span class="reg-body-infor">Nhập thông tin tài khoản</span>
                <form class="reg-form" method="POST" action="{{ url('thongtincanhan') }}">
                    {{ csrf_field() }}

                    <div class="reg-body-row">
                        <div class="reg-left">
                            <p class="reg-body-title">Tên thành viên*</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('name') ? 'error' : '' }}">
                                {{ Form::text('name', $name, ['class'=>'reg-body-input']) }}
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="reg-body-row">
                        <div class="reg-left">
                            <p class="reg-body-title">Số điện thoại</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('phone_number') ? 'error' : '' }}">
                                {{ Form::text('phone_number', $phone_number, ['class'=>'reg-body-input']) }}
                                @if ($errors->has('phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="reg-body-row">
                        <div class="reg-left">
                            <p class="reg-body-title">Mật khẩu cũ*</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('passwordOld') ? 'error' : '' }}">
                                {{ Form::password('passwordOld', ['class'=>'reg-body-input']) }}
                                @if ($errors->has('passwordOld'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('passwordOld') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="reg-body-row">
                        <div class="reg-left">
                            <p class="reg-body-title">Mật khẩu mới</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('password') ? 'error' : '' }}">
                                {{ Form::password('password', ['class'=>'reg-body-input']) }}
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="reg-hr"/>
                    <input type="submit" value="CẬP NHẬT" class="reg-body-button"/>
                </form>
            </div>
@endsection