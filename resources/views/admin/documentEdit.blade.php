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
                <?php
                    $errors     = $data['errors'];

                    $errID      = $errors->has('id') ? 'error' : '';
                    $errTitle   = $errors->has('title') ? 'error' : '';
                    $errContent = $errors->has('content') ? 'error' : '';

                    echo Form::open(['url' => url('admin/document/edit/'.$doc['stt']),'id' => 'myform','method'=>'post']);
                        echo Form::hidden('stt', $doc['stt']);
                        echo '<div class="row-form ">';
                            echo "<span class='span2'>Bảng:</span>";
                            echo "<div class='span5'>";
                                echo Form::radio('hasTable', 0, $doc['hasTable'] == 0 ? true : false);
                                echo '<span for="noTable">Không có bảng</span>';
                                echo Form::radio('hasTable', 1, $doc['hasTable'] == 1 ? true : false);
                                echo '<span for="hasTable">Có bảng</span>';
                            echo "</div>";
                        echo '</div>';
                        echo '<div class="row-form '.$errID.'">';
                            echo "<span class='span2'>Mã:</span>";
                            echo "<div class='span5'>";
                                echo Form::text('id', $doc['id']);
                                if($errors->has('id')) echo '<span>'.$errors->first('id').'</span>';
                            echo "</div>";
                        echo '</div>';

                        echo '<div class="row-form '.$errTitle.'">';
                            echo "<span class='span2'>Tên:</span>";
                            echo "<div class='span5'>";
                                echo Form::text('title', $doc['title']);
                                if($errors->has('title')) echo '<span>'.$errors->first('title').'</span>';
                            echo "</div>";
                        echo "</div>";

                        echo '<div class="row-form">';  
                            echo "<span class='span2'>Danh mục:</span>";
                            echo "<div class='span5'>"; 
                            echo Form::select('cat', $data['options'], $doc['category']);
                            echo "</div>";
                        echo "</div>";

                        echo '<div class="row-form '.$errContent.'">';
                            echo "<span class='span2'>Nội dung:</span>";
                            echo "<div class='span10'>";
                            echo Form::textarea('content', $doc['content'], array('id'=> 'ckeditor'));
                            if($errors->has('content')) echo '<span>'.$errors->first('content').'</span>';
                            echo "</div>";
                        echo "</div>";

                        echo '<div style="width:100%; text-align:center;">'.
                                '<input type="submit" value="Upload!" class="btn" />'. 
                            '</div>';

                    echo Form::close();
                ?>
                </div><!-- end row-fluid -->
            </div><!-- end content -->

            <script>
                initSample();
                // CKEDITOR.replace( 'ckeditor',{
                //     allowedContent: true
                // } );
            </script>

@endsection