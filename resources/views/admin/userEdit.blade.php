@extends('admin.layout')
 
@section('content')
            <div class="content">
                
                <div class="page-header">
                    <div class="icon">
                        <span class="ico-layout-7"></span>
                    </div>
                    @if(!$data['user']['id'])
                    <h1>Thêm mới<small>METRO STYLE ADMIN PANEL</small></h1>
                    @else
                    <h1>Chỉnh sửa {{ $data['user']['name'] }}<small>METRO STYLE ADMIN PANEL</small></h1>
                    @endif
                </div>
                
                <div class="row-fluid">
                    {{ Form::open(['url' => url('admin/user/'.$data['user']['id']),'id' => 'myform','method'=>'post']) }}
                        {{ csrf_field() }}

                        @if($data['uRole']['name'] != STR_ADMIN)
                        <div class="row-form ">
                            <span class='span2'>Kích hoạt:</span>
                            <div class='span2'>
                                {{ Form::radio('isActive', 0, $data['user']['isActive'] == 0 ? true : false) }}
                                <span for="unActive">Không</span>
                            </div>
                            <div class='span2'>
                                {{ Form::radio('isActive', 1, $data['user']['isActive'] == 1 ? true : false) }}
                                <span for="isActive">Có</span>
                            </div>
                        </div>
                        @endif

                        <div class="row-form {{ $errors->has('name') ? 'error' : '' }}">
                            <span class='span2'>Tên:</span>
                            <div class='span5'>
                                {{ Form::text('name', $data['user']['name']) }}
                                @if ($errors->has('name'))
                                    <strong>{{ $errors->first('name') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('email') ? 'error' : '' }}">
                            <span class='span2'>Email:</span>
                            <div class='span5'>
                                {{ Form::text('email', $data['user']['email']) }}
                                @if ($errors->has('email'))
                                    <span>
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('coin') ? 'error' : '' }}">
                            <span class='span2'>Xu:</span>
                            <div class='span5'>
                                {{ Form::text('coin', $data['uCoin']['coin']) }}
                                @if ($errors->has('coin'))
                                    <span>
                                        <strong>{{ $errors->first('coin') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('password') ? 'error' : '' }}">
                            <span class='span2'>Mật khẩu mới:</span>
                            <div class='span5'>
                                {{ Form::password('password') }}
                                @if ($errors->has('password')) {
                                    <span>
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('phone_number') ? 'error' : '' }}">
                            <span class='span2'>Số điện thoại:</span>
                            <div class='span5'>
                                {{ Form::text('phone_number', $data['user']['phone_number']) }}
                                @if ($errors->has('phone_number'))
                                    <span>
                                        <strong>{{ $errors->first('phone_number') }}</strong>
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