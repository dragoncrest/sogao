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
                <?php  
                    echo Form::open(['url' => url('admin/user/'.$data['user']['id']),'id' => 'myform','method'=>'post']);
                        echo csrf_field();

                        echo '<div class="row-form">';
                            echo "<span class='span2'>Tên:</span>";
                            echo "<div class='span5'>";
                                echo Form::text('name', $data['user']['name']);
                                if ($errors->has('name')) {
                                    echo '<span class="help-block">';
                                        echo '<strong>' . $errors->first('name') . '</strong>';
                                    echo '</span>';
                                }
                            echo "</div>";
                        echo "</div>";

                        echo '<div class="row-form">';
                            echo "<span class='span2'>Email:</span>";
                            echo "<div class='span5'>";
                                echo Form::text('email', $data['user']['email']);
                                if ($errors->has('email')) {
                                    echo '<span class="help-block">';
                                        echo '<strong>' . $errors->first('email') . '</strong>';
                                    echo '</span>';
                                }
                            echo "</div>";
                        echo "</div>";

                        echo '<div class="row-form">';
                            echo "<span class='span2'>Xu:</span>";
                            echo "<div class='span5'>";
                                echo Form::text('coin', $data['uCoin']['coin']);
                                if ($errors->has('coin')) {
                                    echo '<span class="help-block">';
                                        echo '<strong>' . $errors->first('coin') . '</strong>';
                                    echo '</span>';
                                }
                            echo "</div>";
                        echo "</div>";

                        echo '<div class="row-form">';
                            echo "<span class='span2'>Mật khẩu mới:</span>";
                            echo "<div class='span5'>";
                                echo Form::password('password');
                                if ($errors->has('password')) {
                                    echo '<span class="help-block">';
                                        echo '<strong>' . $errors->first('password') . '</strong>';
                                    echo '</span>';
                                }
                            echo "</div>";
                        echo "</div>";

                        echo '<div style="width:100%; text-align:center;">'.
                                '<input type="submit" value="Cập nhật!" class="btn" />'. 
                            '</div>';

                    echo Form::close();
                ?>
                </div><!-- end row-fluid -->
            </div><!-- end content -->
@endsection