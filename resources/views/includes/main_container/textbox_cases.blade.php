
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
        <img id="previewImage" src="{{ asset('storage/'.$textbox->image_url) }}" alt="">
        <p class="subValue title">{{$textbox->sub_value}}</p>
    </div>
@endif


