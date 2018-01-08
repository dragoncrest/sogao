        <div class="sidebar">
            
            <div class="top">
                <a href="{{ url('/admin') }}" class="logo"></a>
                <div class="search">
                    <a href="http://sotay.dev"><h2>Local Sotay</h2></a>
                    <!--<div class="input-prepend">
                        <span class="add-on orange"><span class="icon-search icon-white"></span></span>
                        <input type="text" placeholder="search..."/>                                                      
                    </div>-->            
                </div>
            </div>
            <div class="nContainer">                
                <ul class="navigation">         
                    <li class=""><a href="{{ url('/admin') }}" class="blblue">Dashboard</a></li>
                    <li class="<?php if($nav=='doc') echo 'active';?>">
                        <a href="#" class="blyellow">Văn bản</a>
                        <div class="open"></div>
                        <ul>
                            <li><a href="{{ url('/admin/document/edit') }}">Thêm mới văn bản</a></li>
                            
                            <?php foreach($cates as $cat):?>
                            <li><a href="<?php echo url('/admin/document/'.$cat->id)?>"><?php echo $cat->title;?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li  class="<?php if($nav=='cat') echo 'active';?>">
                        <a href="#" class="blgreen">Danh mục</a>
                        <div class="open"></div>
                        <ul>
                            <li><a href="{{ url('/admin/category/edit') }}">Thêm mới</a></li>
                            <li><a href="{{ url('/admin/category') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)" class="blred open_file_manager">Quản lý file</a></li>
                    <li>
                        <a href="{{ url('/admin/users') }}" class="bldblue">Tài khoản</a>
                    </li>
                    <li>
                        <a href="#" class="blpurple">Samples</a>
                        <div class="open"></div>
                        <ul>
                            <li><a href="faq.html">FAQ</a></li>
                            <li><a href="login.html">Login</a></li>
                        </ul>
                    </li>
                    @if (!Auth::guest())
                    <li>
                        <a href="{{ url('admin/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a>
                    </li>
                    @endif
                </ul>
                <a class="close">
                    <span class="ico-remove"></span>
                </a>
            </div>
            <div class="widget">
                <div class="datepicker"></div>
            </div>
            
        </div><!-- end sidebar-->

        <script type="text/javascript">
            $('.open_file_manager').on("click", function() {
                window.open('//sotay.dev/public/assets/admin/js/plugins/elfinder/elfinder.html?lang=vi','name','width=800,height=500');
            });
        </script>