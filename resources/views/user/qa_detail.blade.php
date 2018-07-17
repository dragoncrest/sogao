@extends('layouts.layout')

@section('header')
    <script type="text/javascript" src="{{ url('public/assets/js/document.js') }}"></script>
@endsection

@section('title')
    Sổ tay 56 - Hỏi đáp
@endsection

@section('content')
            <div class="feedback-header"><h2>Hỏi Đáp</h2></div>
            <!-- <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Tiêu đề:</div>
                <div class='qa-col qa-col-r'>
                    {{ Form::text('title', $qa->title, ['class' => 'qa-input', ]) }} 
                </div>
            </div> -->
            <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Chuyên mục:</div>
                <div class='qa-col qa-col-r'>
                    {{ Form::select('category', [null => '-- Chuyên mục --'] + $cats, $qa->category_id, ['class' => 'qa-select', 'disabled']) }}
                </div>
            </div>
            <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Câu hỏi:</div>
                <div class='qa-col qa-col-r'>
                    {{ Form::textarea('question', $qa->question, ['class' => 'qa-textarea', 'rows' => '5']) }}
                </div>
            </div>
            <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Trả lời:</div>
                <div class='qa-col qa-col-r qa-col-detail'>
                    <?php echo $qa->answer; ?>
                </div>
            </div>

            <div id="dialog" class="popup-dialog">
                <div class="pop-close pop-close-but"></div>
                <div class="pop-content"></div>
            </div>

            <script id="dialogStatus-template" type="text/x-custom-template">
                <div class="_content"><%= _str %></div>
                <% if ((typeof(_idDoc) != 'undefined') && _idDoc) { %>
                <div class="_button">
                    <a href="javascript:void(0)" class="pop-but pop-but-close" onclick="hidePopupDialog('#dialog');">Hủy</a>
                    <a href="javascript:void(0)" class="pop-but pop-but-buy" onclick="buyDocument('<%= _idDoc %>')">Đồng ý</a>
                </div>
                <% } %>
                <% if (typeof(_isBuying) != 'undefined') { %>
                <div class="pop-loading">
                    <img src="{{ url('public/assets/images/loading-buy.gif') }}" />
                </div>
                <% } %>
            </script>

            <script type="text/javascript">
                var BUYED   = '{!! BUYED !!}';
                var NOTCOIN = '{!! NOTCOIN !!}';
                var LOGIN   = '{!! LOGIN !!}';
                var BUY     = '{!! BUY !!}';

                var urlDocument = "{!! url('/document/') !!}/";
                var urlHowToBuy = "{!! url('/phi-dich-vu/') !!}/";
                var urlAjaxBuyDocument    = "{!! url('/document/ajaxBuyDocument/') !!}/";
            </script>
@endsection  