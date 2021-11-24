
@if ( $textbox->textboxCase->group === 'heading' )

    <!-- Heading -->
    <div class="{{$textbox->textboxCase->value}}">
        <p class="mainValue">{!!$textbox->replace_main_value!!}</p>
    </div>

@elseif ( $textbox->textboxCase->group === 'text' )

    <!-- Text -->
    <div class="{{$textbox->textboxCase->value}}">
        <p class="mainValue">{!!$textbox->replace_main_value!!}</p>
    </div>

@elseif ( $textbox->textboxCase->group === 'link' )

    <!-- Link -->
    <div class="{{$textbox->textboxCase->value}}">
        <a href="{{$textbox->main_value}}">{{$textbox->sub_value}}</a>
    </div>

@elseif ( $textbox->textboxCase->group === 'image' )

    <!-- Image -->
    <div class="{{$textbox->textboxCase->value}}">
        <img id="previewImage" src="{{ $textbox->image_url }}" alt="">
        <p class="subValue title">{{$textbox->sub_value}}</p>
    </div>
@endif




{{-- @switch($textbox->textbox_case_id)

    @case(1)
        <br>
        <div class="heading1">
            {!!$textbox->replace_main_value!!}
        </div>
        <br>
        @break
    @case(2)
        <br>
        <div class="heading2">
            {!!$textbox->replace_main_value!!}
        </div>
        <br>
        @break
    @case(3)
        <div class="heading3">
            {!!$textbox->replace_main_value!!}
        </div>
        <br>
        @break




    @case(4)
        <div class="normal_text">
            {!!$textbox->replace_main_value!!}
        </div>
        <br>
        @break
    @case(5)
        <div class="important_text">
            <br>
            {!!$textbox->replace_main_value!!}
            <br>
        </div>
        <br>
        @break
    @case(6)
        <div class="emphasized_text">
            <br>
            {!!$textbox->replace_main_value!!}
            <br>
        </div>
        <br>
        @break
    @case(7)
        <div class="quote_text">
            <br>
            {!!$textbox->replace_main_value!!}
            <br>
        </div>
        <br>
        @break
    @case(8)
        <div class="code_text">
            <div class="title">tatle.php</div>
            <div class="text">
                <br>
                {!!$textbox->replace_main_value!!}
                <br>
            </div>
        </div>
        <br>
        @break




    @case(9)
        <div class="link">
            <a href="{{$textbox->main_value}}">{{$textbox->sub_value}}</a>
        </div>
        @break
    @case(10)
        <div class="image">
            <img src="{{ $textbox->image_url }}" alt="">
            <p class="title">{{$textbox->sub_value}}</p>
        </div>
        @break
    @case(11)
        <div class="image_litle">
            <img src="{{ $textbox->image_url }}" alt="">
            <p class="title">{{$textbox->sub_value}}</p>
        </div>
        @break
    @default



@endswitch
 --}}





