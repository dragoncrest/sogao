@extends('layouts.layout')

@section('title')
    Sổ tay 56 - Hỏi đáp
@endsection

@section('content')
            <div class="feedback-header"><h2>Hỏi Đáp</h2></div>
            <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Tiêu đề</div>
                <div class='qa-col qa-col-r'>
                    {{ Form::text('title', $qa->title, ['class' => 'qa-input', ]) }}
                </div>
            </div>
            <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Chuyên mục</div>
                <div class='qa-col qa-col-r'>
                    {{ Form::select('category', [null => '-- Chuyên mục --'] + $cats, $qa->category_id, ['class' => 'qa-select', 'disabled']) }}
                </div>
            </div>
            <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Câu hỏi</div>
                <div class='qa-col qa-col-r'>
                    {{ Form::textarea('question', $qa->question, ['class' => 'qa-textarea', 'rows' => '15']) }}
                </div>
            </div>
            <div class='qa-row clear'>
                <div class='qa-col qa-col-l'>Trả lời</div>
                <div class='qa-col qa-col-r'>
                    {{ Form::textarea('answer', $qa->answer, ['class' => 'qa-textarea', 'rows' => '15']) }}
                </div>
            </div>
@endsection  