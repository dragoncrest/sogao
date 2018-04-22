@extends('layouts.layout')

@section('title')
    {{ empty($currentCat) ? 'Sổ tay 56' : $currentCat->title }}
@endsection

@section('content')
            <?php echo view('templates/search_form', ['currentCat' => $currentCat]);?>

        @if(!empty($currentCat) && !is_null($currentCat))
            <div class="content">
                <div class="block">
                    <div class="head blue">
                        <h2>{{ $currentCat->title }}</h2>
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
                            d.cat = {{ !empty($currentCat) ? $currentCat->id : 0 }};
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
            </script>
        @else
            <h3><i>Danh mục đang được cập nhật</i></h3>
        @endif
@endsection  