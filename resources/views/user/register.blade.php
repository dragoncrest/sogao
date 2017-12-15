@extends('layouts.layout')

@section("content")
            <div class="reg-header"><h2>đăng ký tài khoản</h2></div>
            <div class="reg-body">
                <span class="reg-body-infor">Nhập thông tin tài khoản</span>
                <form class="reg-form" method="POST" action="{{ url('register') }}">
                    {{ csrf_field() }}

                    <p class="reg-body-title">1. Họ và tên</p>
                    <div class="{{ $errors->has('name') ? 'has-error' : '' }}">
                        {{ Form::text('name', null, ['class'=>'reg-body-input']) }}
                        @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>

                    <p class="reg-body-title">2. Email</p>
                    <div class="{{ $errors->has('email') ? 'has-error' : '' }}">
                        {{ Form::email('email', null, ['class'=>'reg-body-input']) }}
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>

                    <p class="reg-body-title">3. Mật khẩu</p>
                    <div class="{{ $errors->has('password') ? 'has-error' : '' }}">
                        {{ Form::password('password', ['class'=>'reg-body-input']) }}
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>

                    <p class="reg-body-title">4. Nhập lại mật khẩu</p>
                    <div class="{{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                        {{ Form::password('password_confirmation', ['class'=>'reg-body-input']) }}
                        @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>

                    <hr class="reg-hr"/>
                    <input type="submit" value="ĐĂNG KÝ" class="reg-body-button"/>
                </form>
            </div>
@endsection