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
                <span class="sidebar-header sidebar-header-white sidebar-header-login">
                    Xin chào: {{ Auth::user()->name }}
                </span>

                <div class="sidebar-body">
                    <div class="coin clear">
                        <img src="{{ url('public/assets/images/coin.gif') }}"/>
                        <span>{{ $coin }}</span>
                    </div>
                    <a href="{{ url('logout') }}">Đăng xuất</a>
                </div>
            @else
                <span class="sidebar-header sidebar-header-white sidebar-header-login">ĐĂNG NHẬP</span>
                <div class="sidebar-body">
                    <form id="login-form" method="POST" action="{{ url('login') }}">
                        {{ csrf_field() }}

                        <div class="full login-margin">
                            <input class="login-input" type="text" name="email" placeholder="Email" />
                        </div>

                        <div class="full login-margin">
                            <input class="login-input" type="password" name="password" placeholder="Mật khẩu" />
                        </div>

                        <div class="full login-margin" style="text-align:right;">
                            <input class="login-submit" type="submit" value="ĐĂNG NHẬP" />
                        </div>                       
                        <div class="full">
                            <a href="{{ url('register') }}">ĐĂNG KÝ MỚI</a>
                            <i><a style="float: right;" href="{{ url('password/reset') }}">QUÊN MẬT KHẨU</a></i>
                        </div>
                    </form>
                </div>
            @endif

            </div><!-- end login sidebar -->
        </div><!-- end body col 1 -->