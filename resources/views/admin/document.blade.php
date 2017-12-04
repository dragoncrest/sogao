@extends('admin.layout')

@section('content')
            <div class="content">

                <div class="page-header">
                    <div class="icon">
                        <span class="ico-layout-7"></span>
                    </div>
                    <h1>Danh sách <?php echo $data['catTitle'];?><small>METRO STYLE ADMIN PANEL</small></h1>
                </div>

                <div class="row-fluid">
                    <div class="span12">
                        <div class="block">
                            @if (session('delete'))
                                <div class="alert alert-success">
                                    Đã xóa <b>{{ session('delete') }}</b>
                                </div> 
                            @endif
                            <div class="head purple">
                                <div class="icon"><span class="ico-layout-9"></span></div>
                                <h2>AJAX Data load</h2>
                                <!--<a class="but-load" href="#" onClick="creatable();">
                                    <div class="icon"><span class="ico-refresh"></span></div>
                                </a>-->
                                <ul class="buttons">
                                    <li><a href="#" onClick="source('table_sort_ajax'); return false;"><div class="icon"><span class="ico-info"></span></div></a></li>
                                </ul>
                            </div>
                            <div class="data-fluid">
                                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">STT</th>
                                            <th width="10%">Mã</th>
                                            <th width="55%">Tên</th>
                                            <th width="15%">Ngày sửa</th>
                                            <th class="TAC">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div> 
                        </div><!-- end block -->
                    </div><!-- end span12 -->
                </div><!-- end row-fluid -->
            </div><!-- end content -->

            <script type="text/javascript">
                var deleteUrl = '{!! url('/admin/document/delete/') !!}';
                $(".aTable").DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax":{ 
                        "url": "<?php echo url('/admin/document/ajax');?>",
                        "data": function ( d ) {
                            d.cat = <?php echo $data['catID'];?>;
                        }
                    },
                    "columnDefs": [{
                        "targets": [ 0 ], //first column / numbering column
                        "orderable": false, //set not orderable
                    }],
                    "createdRow": function ( row, data, index ) {
                        var idDocument = $(row).find('.delete-').attr('id');
                        $(row).attr('id', idDocument);
                    }
                });
            </script>
@endsection  