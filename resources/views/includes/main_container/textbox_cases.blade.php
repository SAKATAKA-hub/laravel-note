@switch($textbox->textbox_case_id )


    @case(1)
        <div class="heading1">
            {!!$textbox->replace_main_value!!}
        </div>
        @break
    @case(2)
        <div class="heading2">
            {!!$textbox->replace_main_value!!}
        </div>
        @break
    @case(3)
        <div class="heading3">
            {!!$textbox->replace_main_value!!}
        </div>
        @break




    @case(4)
        <div class="normal_text">
            {!!$textbox->replace_main_value!!}
        </div>
        @break
    @case(5)
        <div class="important_text">
            {!!$textbox->replace_main_value!!}
        </div>
        @break
    @case(6)
        <div class="emphasized_text">
            {!!$textbox->replace_main_value!!}
        </div>
        @break
    @case(7)
        <div class="quote_text">
            {!!$textbox->replace_main_value!!}
        </div>
        @break
    @case(8)
        <div class="code_text">
            <div class="title">tatle.php</div>
            <div class="text">
                {!!$textbox->replace_main_value!!}
            </div>
        </div>
        @break




    @case(9)
        <div class="link">
            <a href="{{$textbox->main_value}}">{{$textbox->sub_value}}</a>
        </div>
        @break
    @case(10)
        <div class="image">
            <img src="{{ asset('storage/'.$textbox->main_value) }}" alt="">
            <p class="title">{{$textbox->sub_value}}</p>
        </div>
        @break
    @case(11)
        <div class="image_litle">
            <img src="{{ asset('storage/'.$textbox->main_value) }}" alt="">
            <p class="title">{{$textbox->sub_value}}</p>
        </div>
        @break
    @default


@endswitch
