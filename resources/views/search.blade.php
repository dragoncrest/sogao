@extends('layouts.layout')

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
                                    <th width="15%">Ngày</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div> 
                </div><!-- end block -->
            </div><!-- end content -->
            
            <script type="text/javascript">
                $(".aTable").DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax":{
                        "url": "<?php echo url('/home/ajaxTable');?>",
                        "data": function ( d ) {
                            d.cat = <?php echo $catID;?>;
                        }
                    },
                    "columnDefs": [{
                        "targets": [ 0 ], //first column / numbering column
                        "orderable": false, //set not orderable
                    }],
                });
            </script>
@endsection  