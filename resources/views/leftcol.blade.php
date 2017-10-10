        <div class="body-col-1">
            <div class="sidebar sidebar-blue">
                <!--<span class="sidebar-header sidebar-header-white"><img src="{{ url('public/assets/images/dot.png') }}"/> CHUYÊN MỤC</span>-->
                <ul class="sidebar-body sidebar-main">
                    <li>
                        <a href="{{ url('document/0') }}">trình tự đầu tư</a>
                        <ul class="sub-menu">
                            <li><a href="{{ url('document/1') }}">chủ trương đầu tư</a></li>
                            <li><a href="{{ url('document/2') }}">quyết định đầu tư</a></li>
                            <li><a href="{{ url('document/3') }}">thực hiện đầu tư</a></li>
                            <li><a href="{{ url('document/4') }}">kết thúc đầu tư</a></li>
                        </ul>
                    </li>
                    <li><a href="">quản lý đấu thầu</a></li>
                    <li><a href="">quản lý chi phí</a></li>
                    <li><a href="">quản lý hợp đồng</a></li>
                    <li><a href="">thuật ngữ</a></li>
                    <li><a href="">quy định pháp luật</a></li>
                    <li><a href="">tiện ích</a></li>
                </ul>
            </div><!-- end menu sidebar -->
            
            <div class="sidebar sidebar-skyblue">
                                                
            @if(Auth::user())
                <span class="sidebar-header sidebar-header-white sidebar-header-login">
                    Xin chào: <b>{{ Auth::user()->name }}</b>
                </span>
                
                <div class="sidebar-body">
                    <a href="{{ url('logout/u') }}">Đăng xuất</a>
                </div>
            @else
                <span class="sidebar-header sidebar-header-white sidebar-header-login">ĐĂNG NHẬP</span>
                <div class="sidebar-body">
                    <form id="login-form" method="POST" action="{{ url('login') }}">
                        {{ csrf_field() }}
                        
                        <div class="full login-margin">
                            <span>Email</span>
                            <input class="login-input" type="text" name="email" />
                        </div>
                    @if ($errors->has('email'))
                        <div class="login-err error">
                            {{ $errors->first('email') }}
                        </div>
                    @endif     
                                       
                        <div class="full login-margin">
                            <span>MẬT KHẨU</span>
                            <input class="login-input" type="password" name="password" />
                        </div>
                    @if ($errors->has('password'))
                        <div class="login-err error">
                            {{ $errors->first('password') }}
                        </div>
                    @endif  
                                          
                        <div class="full login-margin" style="text-align:right;"><input class="login-submit" type="submit" value="ĐĂNG NHẬP" /></div>                       
                    <div class="full">
                        <a href="{{ url('register') }}">ĐĂNG KÝ MỚI</a>
                        <i><a style="float: right;" href="{{ url('password/reset') }}">QUÊN MẬT KHẨU</a></i>
                    </div>
                    </form>
                </div>
            @endif
                
            </div><!-- end login sidebar -->
        </div><!-- end body col 1 -->