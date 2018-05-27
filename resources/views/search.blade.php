@extends('layouts.layout')

@section('title')
    Tìm kiếm
@endsection

@section('content')
            <div class="content">
                <div class="block">
                    <div class="head blue">
                        <h2>Bảng tìm kiếm</h2>
                    </div>
                    <div class="data-fluid">
                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="55%">Tên</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div> 
                </div><!-- end block -->
            </div><!-- end content -->

            <script type="text/javascript">
                var table = $(".aTable").DataTable( {
                    "language": {
                        search: "",
                        searchPlaceholder: "Điền từ khóa",
                        zeroRecords: "Không có dữ liệu phù hợp",
                    },
                    "dom": '<"dataTables_search dataTables_length"f><"dataTables_btnSearch"><"dataTables_category">lrtip',
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax":{
                        "url": "<?php echo url('/document/ajaxTable');?>",
                        "data": function ( d ) {
                            d.cat = $(".dataTables_select option").filter(":selected").val();
                        }
                    },
                    "columnDefs": [{
                        "targets": [ 0 ], //first column / numbering column
                        "orderable": false, //set not orderable
                    }],
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
                var category = '<?php echo Form::select('category', [null => '-- Chuyên mục --'] + $cats, '', ['class' => 'dataTables_select']) ?>';
                $("div.dataTables_btnSearch").html('<button class="btn">Tìm kiếm</a>');
                $("div.dataTables_category").html('<span>Chuyên mục</span>' + category);
                $('.dataTables_btnSearch .btn').on( 'click', '', function () {
                    table
                    .search($('.dataTables_filter input').val())
                    .draw();
                });
            </script>
@endsection  