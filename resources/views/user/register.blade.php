@extends('layouts.layout')

@section("content")
            <div class="reg-header"><h2>đăng ký tài khoản</h2></div>
            <div class="reg-body">
                <span class="reg-body-infor">Nhập thông tin tài khoản</span>
                <form class="reg-form" method="POST" action="{{ url('register') }}">
                    {{ csrf_field() }}
                    <div class="reg-body-row">
                        <div class="reg-left">
                            <p class="reg-body-title">Tên thành viên</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('name') ? 'has-error' : '' }}">
                                {{ Form::text('name', null, ['class'=>'reg-body-input']) }}
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
                            <p class="reg-body-title">Email</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('email') ? 'has-error' : '' }}">
                                {{ Form::email('email', null, ['class'=>'reg-body-input']) }}
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="reg-body-row">
                        <div class="reg-left">
                            <p class="reg-body-title">Mật khẩu</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('password') ? 'has-error' : '' }}">
                                {{ Form::password('password', ['class'=>'reg-body-input']) }}
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="reg-body-row">
                        <div class="reg-left">
                            <p class="reg-body-title">Nhập lại mật khẩu</p>
                        </div>
                        <div class="reg-right">
                            <div class="reg-body-ip {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                {{ Form::password('password_confirmation', ['class'=>'reg-body-input']) }}
                                @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
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
                            <div class="reg-body-ip {{ $errors->has('phone_number') ? 'has-error' : '' }}">
                                {{ Form::text('phone_number', null, ['class'=>'reg-body-input']) }}
                                @if ($errors->has('phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="reg-hr"/>
                    <p class="reg-body-agree"><i>Tôi đã đọc và đồng ý với <a href="">Quy ước sử dụng</a></i></p>
                    <input type="submit" value="ĐĂNG KÝ" class="reg-body-button"/>
                </form>
            </div>
@endsection