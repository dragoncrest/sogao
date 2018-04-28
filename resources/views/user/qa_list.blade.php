@extends('layouts.layout')

@section('title')
    Sổ tay 56 - Hỏi đáp
@endsection

@section('content')
            <div class="content">
                <div class="block">
                    <div class="head blue">
                        <h3>Danh sách hỏi đáp</h3>
                    </div>
                    <div class="data-fluid">
                        <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="80%">Tên</th>
                                    <th width="">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div><!-- end block -->
            </div><!-- end content -->

            <script type="text/javascript">
                var table = $(".aTable").DataTable({
                    "language": {
                        search: "",
                        searchPlaceholder: "Điền từ khóa",
                        zeroRecords: "Không có dữ liệu phù hợp",
                    },
                    "dom": '<"dataTables_search dataTables_length"f><"dataTables_btnSearch"><"dataTables_category">l<"dataTables_addQA">rtip',
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax":{ 
                        "url": "<?php echo url('/hoidaps/ajaxListQA');?>",
                        "data": function ( d ) {
                            d.catId = $(".dataTables_select option").filter(":selected").val();
                        }
                    },
                    "columnDefs": [{ 
                        "targets": [ 0 ],
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
                var button = '<a href="' + "{{ url('/hoidap') }}" + '" class="btn">Gửi câu hỏi</a>';

                $("div.dataTables_btnSearch").html('<button class="btn">Tìm kiếm</a>');
                $("div.dataTables_category").html('<span>Chuyên mục</span>' + category);
                $("div.dataTables_addQA").html(button);
                $('.dataTables_btnSearch .btn').on( 'click', '', function () {
                    table
                    .columns(1)
                    .search($(this).text())
                    .draw();
                });
            </script>
@endsection  