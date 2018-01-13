        <div class="body-col-1">
            <div class="sidebar sidebar-blue">
                <!--<span class="sidebar-header sidebar-header-white"><img src="{{ url('public/assets/images/dot.png') }}"/> CHUYÊN MỤC</span>-->
                <ul class="sidebar-body sidebar-main">
                    <li><a href="{{ url('category/quy-dinh-phap-luat') }}">quy định pháp luật</a></li>
                    <li><a href="{{ url('category/giai-thich-tu-ngu') }}">giải thích từ ngữ</a></li>
                    <li><a href="{{ url('category/bieu-mau') }}">biểu mẫu</a></li>
                    <li>
                        <a href="{{ url('document/thu-tuc-dau-tu') }}">thủ tục đầu tư</a>
                        <ul class="sub-menu">
                            <li><a href="{{ url('document/trinh-tu-thu-tuc-quyet-dinh-chu-truong-dau-tu-xay-dung') }}">chủ trương đầu tư</a></li>
                            <li><a href="{{ url('document/trinh-tu-thu-tuc-quyet-dinh-dau-tu-xay-dung') }}">quyết định đầu tư</a></li>
                            <li><a href="{{ url('document/trinh-tu-thu-tuc-thuc-hien-dau-tu-xay-dung') }}">thực hiện đầu tư</a></li>
                            <li><a href="{{ url('document/trinh-tu-thu-tuc-ket-thuc-dau-tu-xay-dung') }}">kết thúc đầu tư</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('document/quan-ly-dau-thau') }}">quản lý đấu thầu</a></li>
                    <li><a href="{{ url('document/quan-ly-chi-phi') }}">quản lý chi phí</a></li>
                    <li><a href="{{ url('document/quan-ly-hop-dong') }}">quản lý hợp đồng</a></li>
                    <li><a href="{{ url('document/tien-ich') }}">tiện ích</a></li>
                    <li><a href="{{ url('document/ky-nang-mem') }}">kỹ năng mềm</a></li>
                </ul>
            </div><!-- end menu sidebar -->

            <div class="sidebar sidebar-skyblue">

            @if(Auth::user())
                <div class="sidebar-header sidebar-header-white sidebar-header-login">
                    <div class="sidebar-header-logged">Xin chào: {{ Auth::user()->name }}</div>
                </div>

                <div class="sidebar-body">
                    <div class="coin clear">
                        <img src="{{ url('public/assets/images/coin.gif') }}"/>
                        <span>{{ $coin }}</span>
                    </div>
                    <a href="{{ url('logout') }}">Đăng xuất</a>
                </div>
            @else
                <div class="sidebar-header sidebar-header-white sidebar-header-login">
                    <div class="sidebar-header-logged">ĐĂNG NHẬP</div>
                </div>
                <?php
                    $emailError = $passwordError = '';
                    if (Session::has('isLogin')) {
                        if ($errors->has('email')) {
                            $emailError = 'has-error';
                        }
                        if ($errors->has('password')) {
                            $passwordError = 'has-error';
                        }
                    }
                ?>
                <div class="sidebar-body">
                    <form id="login-form" method="POST" action="{{ url('login') }}">
                        {{ csrf_field() }}

                        <div class="full login-margin {{ $emailError }}">
                            <input class="login-input" type="text" name="email" placeholder="Email" />
                            @if ($emailError)
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                            @endif
                        </div>

                        <div class="full login-margin {{ $passwordError }}">
                            <input class="login-input" type="password" name="password" placeholder="Mật khẩu" />
                            @if ($passwordError)
                            <span class="help-block">
                                {{ $errors->first('password') }}
                            </span>
                            @endif
                        </div>

                        <div class="full login-margin clear">
                            <div class="login-remember"><input type="checkbox" name="remember">Duy trì</div>
                            <input class="login-submit" type="submit" value="ĐĂNG NHẬP" />
                        </div>                       
                        <div class="full login login-margin">
                            <a href="{{ url('register') }}">ĐĂNG KÝ MỚI</a>
                            <i><a style="float: right;" href="{{ url('password/reset') }}">QUÊN MẬT KHẨU</a></i>
                        </div>
                    </form>
                </div>
            @endif

            </div><!-- end login sidebar -->
        </div><!-- end body col 1 -->