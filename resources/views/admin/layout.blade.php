<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--[if gt IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <![endif]-->
    <title>Local Admin Panel</title>
    <link rel="icon" type="image/ico" href="{{ url('public/assets/admin/favicon.ico') }}"/>

    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/admin/css/stylesheets.css') }}" />
    <?php if($data['nav']) echo '<link rel="stylesheet" type="text/css" href="'.url("public/assets/admin/js/plugins/datatables/jquery.dataTables.min.css").'"  />';?>    
    <?php if($data['nav']) echo '<link rel="stylesheet" type="text/css" href="'.url("public/assets/js/fancybox/jquery.fancybox.css").'"  />';?>   

    <!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="{{ url('public/assets/admin/css/ie.css') }}" />
        <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/other/lte-ie7.js') }}'></script>
    <![endif]-->

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jquery/jquery-1.9.1.min.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jquery/jquery-migrate-1.1.1.min.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jquery/globalize.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/other/excanvas.js') }}'></script>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/other/jquery.mousewheel.min.js') }}'></script>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/bootstrap/bootstrap.min.js') }}'></script>
    
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/cookies/jquery.cookies.2.2.0.min.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/cookies/jquery.storageapi.min.js') }}'></script>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/uniform/jquery.uniform.min.js') }}'></script> 
                          
    <?php if($data['nav']) echo "<script type='text/javascript' src='".url('public/assets/admin/js/plugins/datatables/jquery.dataTables.min.js')."'></script> ";?>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jflot/jquery.flot.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jflot/jquery.flot.stack.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jflot/jquery.flot.pie.js') }}'></script>
    <!--<script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/jflot/jquery.flot.resize.js') }}'></script>-->

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/sparklines/jquery.sparkline.min.js') }}'></script>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') }}'></script>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/uniform/jquery.uniform.min.js') }}'></script>

    <?php if($data['nav']=='doc'):?>
        <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/ckeditor/new/ckeditor.js') }}'></script>
        <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/ckeditor/new/sample.js') }}'></script>
        <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/cleditor/jquery.cleditor.js') }}'></script>

        <script type='text/javascript' src='{{ url('public/assets/js/fancybox/jquery.fancybox.min.js') }}'></script>
    <?php endif;?>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/shbrush/XRegExp.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/shbrush/shCore.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/shbrush/shBrushXml.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/shbrush/shBrushJScript.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins/shbrush/shBrushCss.js') }}'></script>

    <script type='text/javascript' src='{{ url('public/assets/admin/js/plugins.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/charts.js') }}'></script>
    <script type='text/javascript' src='{{ url('public/assets/admin/js/actions.js') }}'></script>

    <script type='text/javascript' src='{{ url('public/assets/js/custom.js') }}'></script>
</head>
<body>
    <div id="loader"><img src="{{ url('public/assets/images/loader.gif') }}"/></div>
    <div class="wrapper">

        <?php echo view('admin.sidebar', $data);?>

        <div class="body">
            <?php echo view('admin.navigation');?>

            @yield('content')
        </div><!-- end body -->

    </div><!-- end wrapper -->

    <div class="dialog" id="source" style="display: none;" title="Source"></div>

</body>
</html>
