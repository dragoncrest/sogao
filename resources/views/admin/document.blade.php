@extends('admin.layout')

@section('content')
            <div class="content">

                <div class="page-header">
                    <div class="icon">
                        <span class="ico-layout-7"></span>
                    </div>
                    <h1>Danh sách văn bản<small>METRO STYLE ADMIN PANEL</small></h1>
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
                    "dom": 'l<"dataTables_category">frtip',
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax":{ 
                        "url": "<?php echo url('/admin/document/ajax');?>",
                        "data": function ( d ) {
                            d.cat = $(".dataTables_select option").filter(":selected").val();
                            if (d.cat == undefined) {
                                d.cat = 0;
                            }
                        }
                    },
                    "columnDefs": [{
                        "targets": [ 0 ], //first column / numbering column
                        "orderable": false, //set not orderable
                    }],
                    "createdRow": function ( row, data, index ) {
                        var idDocument = $(row).find('.delete-').attr('id');
                        $(row).attr('id', idDocument);
                    },
                    "initComplete": function(){
                        var api = this.api();
                        $('#DataTables_Table_0_filter input')
                            .off('.DT')
                            .on('keyup.DT', function (e) {
                                if (e.keyCode == 13) {
                                    api.search(this.value).draw();
                                }
                            });
                    },
                });
                var category = '<?php echo Form::select('category', [0 => '-- Chuyên mục --'] + $data['cats'], '', ['class' => 'dataTables_select']) ?>';
                $("div.dataTables_category").html(category);
            </script>
@endsection  