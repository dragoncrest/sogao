@extends('layouts.layout')

@section('title')
    {!! $title ? $title : 'Sổ tay 56 - Tìm kiếm' !!}
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
                $(".aTable").DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax":{
                        "url": "<?php echo url('/document/ajaxTable');?>",
                        "data": function ( d ) {
                            d.cat = <?php echo isset($currentCat->id) ? $currentCat->id : 0;?>;
                        }
                    },
                    "columnDefs": [{
                        "targets": [ 0 ], //first column / numbering column
                        "orderable": false, //set not orderable
                    }],
                });
            </script>
@endsection  