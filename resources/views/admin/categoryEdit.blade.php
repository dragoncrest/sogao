@extends('admin.layout')
 
@section('content')
            <?php $cat = $data['cat'];?>
            <div class="content">
                
                <div class="page-header">
                    <div class="icon">
                        <span class="ico-layout-7"></span>
                    </div>
                    @if(!$cat['id'])
                    <h1>Thêm mới<small>METRO STYLE ADMIN PANEL</small></h1>
                    @else
                    <h1>Chỉnh sửa mục {{ $cat['title'] }}<small>METRO STYLE ADMIN PANEL</small></h1>
                    @endif
                </div>
                
                <div class="row-fluid">
                <?php  
                    $errTitle='';
                    $errors = $data['errors'];
                   
                    if($errors->has('title')) $errTitle = 'error';                                
                    
                    echo Form::open(['url' => url('admin/category/edit'),'id' => 'myform','method'=>'post']);
                        echo Form::hidden('id', $cat['id']);

                        echo '<div class="row-form '.$errTitle.'">';
                            echo "<span class='span2'>Tên:</span>";
                            echo "<div class='span5'>";
                                echo Form::text('title', $cat['title']);
                                if($errors->has('title')) echo '<span>'.$errors->first('title').'</span>';
                            echo "</div>";
                        echo "</div>";
                        
                        echo '<div class="row-form">';  
                            echo "<span class='span2'>Danh mục Cha:</span>";
                            echo "<div class='span5'>"; 
                            echo Form::select('parent', $data['options'], $cat['parent']);
                            echo "</div>";
                        echo "</div>";

                        echo '<div class="row-form ">';
                            echo "<span class='span2'>Tìm kiếm:</span>";
                            echo "<div class='span6'>";
                                echo Form::radio('searchable', 0, $cat['searchable'] == 0 ? true : false);
                                echo '<span for="nosearchable" class="rd-txt">Không</span>';
                                echo Form::radio('searchable', 1, $cat['searchable'] == 1 ? true : false);
                                echo '<span for="searchable">Có (hiển thị ở chức năng tìm kiếm)</span>';
                            echo "</div>";
                        echo '</div>';

                        echo '<div class="row-form ">';
                            echo "<span class='span2'>Download:</span>";
                            echo "<div class='span6'>";
                                echo Form::radio('isDownload', 0, $cat['isDownload'] == 0 ? true : false);
                                echo '<span for="noDownload" class="rd-txt">Không tải văn bản</span>';
                                echo Form::radio('isDownload', 1, $cat['isDownload'] == 1 ? true : false);
                                echo '<span for="isDownload">Được tải văn bản</span>';
                            echo "</div>";
                        echo '</div>';

                        echo '<div style="width:100%; text-align:center;">'.
                                '<input type="submit" value="Upload!" class="btn" />'. 
                            '</div>';  
                        
                    echo Form::close(); 
                ?>                    
                </div><!-- end row-fluid -->
            </div><!-- end content -->
@endsection