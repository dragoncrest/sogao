<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ url('public/favicon.ico') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/font.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/js/fancybox/jquery.fancybox.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/admin/css/table.css') }}" />

    <script type="text/javascript" src="{{ url('public/assets/js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/underscore-min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/fancybox/jquery.fancybox.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/assets/admin/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/custom.js') }}"></script>
    @yield('header')
</head>
<body>
    <div class="menu">
        <div class="wrapper">
            <ul class="menu-1 clear">
                <li class="menu-title menu-home"><a href="{{ url('') }}">chỉ dẫn pháp lý</a></li>
                <li class="menu-title menu-title-1"><a href="{{ url('/gioi-thieu') }}">giới thiệu</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-1"><a href="{{ url('/phi-dich-vu') }}">dịch vụ</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-1"><a href="{{ url('/quy-uoc-su-dung') }}">quy ước</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-1"><a href="{{ url('/huong-dan-su-dung') }}">hướng dẫn sử dụng</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-1"><a href="{{ url('/chinh-sach-bao-mat-thong-tin') }}">chính sách bảo mật</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-1"><a href="{{ url('/lien-he') }}">liên hệ</a></li>
            </ul>
            <ul class="menu-2 clear">
                <li class="menu-title menu-title-2"><a href="{{ url('') }}">trang chủ</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-2"><a href="{{ url('/document/tong-quan-ve-du-an-dau-tu-xay-dung') }}">thủ tục đầu tư</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-2"><a href="{{ url('/document/quan-ly-dau-thau') }}">đấu thầu</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-2"><a href="{{ url('/document/quan-ly-chi-phi') }}">chi phí</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-2"><a href="{{ url('/document/quan-ly-hop-dong') }}">hợp đồng</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-2"><a href="{{ url('/hoidaps') }}">hỏi đáp</a></li>
                <li class="menu-separate"></li>
                <li class="menu-title menu-title-2"><a href="{{ url('/search') }}">tìm kiếm</a></li>
            </ul>
        </div>
    </div>

    <div class="header-hr"></div><!-- end header -->

    <div class="wrapper clear">
        <?php echo view('leftcol');?><!-- end body col 1 -->

        <div class="body-col-2">

            @yield('content')

        </div><!-- end body col 2 -->
    </div><!-- end main body wrapper -->

    <?php echo view('footer');?>

</body>
</html>