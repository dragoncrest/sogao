<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>{!! $title !!}</title>
    
    <link rel="shortcut icon" href="{{ url('public/favicon.ico') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/font.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/js/fancybox/jquery.fancybox.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/admin/css/table.css') }}" />
    
    <script type="text/javascript" src="{{ url('public/assets/js/jquery-1.11.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/fancybox/jquery.fancybox.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/assets/admin/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/custom.js') }}"></script>
</head>
<body>
    <div class="banner">
        <img src="{{ url('public/assets/images/banner.png') }}" />
    </div>
    
    <div class="menu">
        <div class="wrapper">
            <ul class="clear">
                <li><a href="{{ url('') }}">trang chủ</a></li>
                <li class="menu-separate"></li>
                <li><a href="{{ url('/gioi-thieu') }}">giới thiệu</a></li>
                <li class="menu-separate"></li>
                <li><a href="{{ url('/cac-goi-dich-vu') }}">gói dịch vụ</a></li>
                <li class="menu-separate"></li>
                <li><a href="{{ url('/huong-dan-su-dung') }}">hướng dẫn</a></li>
                <li class="menu-separate"></li>
                <li><a href="{{ url('/huong-dan-su-dung') }}">liên hệ</a></li>
                <li class="menu-separate"></li>
                <li><a href="{{ url('/quy-uoc-su-dung') }}">quy ước</a></li>
            </ul>
        </div>
    </div>
    
    <div class="header-hr"></div><!-- end header -->

    <div class="wrapper clear">
        <?php echo view('leftcol');?><!-- end body col 1 -->

        <div class="body-col-2"> 
            
            <form id="sear-i" class="sear-form clear" method="post" onsubmit="return $.CheckSearch();"  action="/search">
                {{ csrf_field() }}
                <!--<input id="sear-inp" class="sear-inp" type="text" name="search" value="tìm kiếm....." />-->
                <select class="sear-select" name="cat">
                    <option value="0">tất cả</option>
                @foreach($cates as $cat)
                    <option <?php if($cat->id==$catID) echo "selected";?> value="{!! $cat->id !!}">{!! $cat->title !!}</option>
                @endforeach
                </select>
                <input class="sear-but" type="submit" value="" />
            </form><!-- end search form -->
                               
            @yield('content')    
                
        </div><!-- end body col 2 -->
    </div><!-- end main body wrapper -->
    
    <?php echo view('footer');?>

</body>
</html>