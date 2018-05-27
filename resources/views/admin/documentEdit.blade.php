@extends('admin.layout')

@section('content')
            <?php $doc = $data['doc'];?>
            <div class="content">
                
                <div class="page-header">
                    <div class="icon">
                        <span class="ico-layout-7"></span>
                    </div>
                    @if(!$doc['stt'])
                        <h1>Thêm mới<small>METRO STYLE ADMIN PANEL</small></h1>
                    @else
                        <h5>Chỉnh sửa: {{ $doc['title'] }}</h5><h1><small>METRO STYLE ADMIN PANEL</small></h1>
                    @endif
                </div>

                <div class="row-fluid">
                    @if($doc['stt'])
                        <?php $id = ($doc['id']) ? $doc['id'] : $doc['stt'];?>
                        <div class="row-form">
                            <!--<a data-fancybox data-type="ajax" data-src="<?php //echo url('document/ajaxThutuc/'.$id);?>" href="javascript:;">
                                Xem bài <?php// echo $id;?>
                            </a>-->
                            <a href="<?php echo url('document/'.$id);?>" target="_blank">
                                Xem bài <?php echo $id;?>
                            </a>
                        </div>
                    @endif

                    <?php $errors = $data['errors']; ?>
                    {{ Form::open(['url' => url('admin/document/edit/'.$doc['stt']),'id' => 'myform','method'=>'post']) }}
                        {{ csrf_field() }}

                        {{ Form::hidden('stt', $doc['stt']) }}

                        <div class="row-form ">
                            <span class='span2'>Bảng:</span>
                            <div class='span2'>
                                {{ Form::radio('hasTable', 0, $doc['hasTable'] == 0 ? true : false) }}
                                <span for="noTable">Không có bảng</span>
                            </div>
                            <div class='span2'>
                                {{ Form::radio('hasTable', 1, $doc['hasTable'] == 1 ? true : false) }}
                                <span for="hasTable">Có bảng</span>
                            </div>
                        </div>

                        <div class="row-form ">
                            <span class='span2'>Download:</span>
                            <div class='span2'>
                                {{ Form::radio('isDownload', 0, $doc['isDownload'] == 0 ? true : false) }}
                                <span for="noDownload">Không tải văn bản</span>
                            </div>
                            <div class='span2'>
                                {{ Form::radio('isDownload', 1, $doc['isDownload'] == 1 ? true : false) }}
                                <span for="isDownload">Được tải văn bản</span>
                            </div>
                        </div>

                        <div class="row-form ">
                            <span class='span2'>Thu phí:</span>
                            <div class='span2'>
                                {{ Form::radio('isBuy', 0, $doc['isBuy'] == 0 ? true : false) }}
                                <span for="noBuy">Miễn phí</span>
                            </div>
                            <div class='span2'>
                                {{ Form::radio('isBuy', 1, $doc['isBuy'] == 1 ? true : false) }}
                                <span for="isBuy">Mất phí</span>
                            </div>
                        </div>

                        <div class="row-form ">
                            <span class='span2'>LV:</span>
                            <div class='span2'>
                                {{ Form::radio('hasLV', 0, $doc['hasLV'] == 0 ? true : false) }}
                                <span for="noTable">Không</span>
                            </div>
                            <div class='span2'>
                                {{ Form::radio('hasLV', 1, $doc['hasLV'] == 1 ? true : false) }}
                                <span for="hasTable">Có</span>
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('id') ? 'error' : '' }}">
                            <span class='span2'>Mã:</span>
                            <div class='span5'>
                                {{ Form::text('id', $doc['id']) }}
                                @if($errors->has('id'))
                                    <span>{{ $errors->first('id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('title') ? 'error' : '' }}">
                            <span class='span2'>Tên:</span>
                            <div class='span5'>
                                {{ Form::text('title', $doc['title']) }}
                                @if($errors->has('title'))
                                    <span>{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row-form">  
                            <span class='span2'>Danh mục:</span>
                            <div class='span5'> 
                                {{ Form::select('cat', $data['options'], $doc['category_id']) }}
                            </div>
                        </div>

                        <div class="row-form {{ $errors->has('content') ? 'error' : '' }}">
                            <span class='span2'>Nội dung:</span>
                            <div class='span10'>
                                {{ Form::textarea('content', $doc['content'], array('id'=> 'ckeditor')) }}
                                @if($errors->has('content'))
                                    <span>{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>

                        <div style="width:100%; text-align:center;">
                            <input type="submit" value="Lưu!" class="btn" />
                        </div>

                    {{ Form::close() }}
                </div><!-- end row-fluid -->
            </div><!-- end content -->

            <script>
                initSample();
                // CKEDITOR.replace( 'ckeditor',{
                //     allowedContent: true
                // } );
            </script>

@endsection