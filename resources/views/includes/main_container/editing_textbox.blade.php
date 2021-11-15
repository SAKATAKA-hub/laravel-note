<div class="editing_textbox">

    <div class="alert alert-danger text-center fs-5" role="alert">
        ・・・  編集中テキストボックス  ・・・
    </div>


    <!-- editingHeading -->
    @if ( !(isset($edit_textbox_case)&&($edit_textbox_case->group === 'heading')) ) <!-- ( create ) -->

        <div class="editing_box hidden" id="editingHeading">
            <p class="mainValue">※見出しを入力してください。</p>
        </div>

    @else <!-- ( edit ) -->

        <div class="editing_box {{$edit_textbox_case->value}}" id="editingHeading">
            <p class="mainValue">{!!$edit_textbox->replace_main_value!!}</p>
        </div>

    @endif


    <!-- editingText -->
    @if ( !(isset($edit_textbox_case)&&($edit_textbox_case->group === 'text')) )

        <div class="editing_box hidden" id="editingText">
            <br>
            <p class="mainValue">※文章を入力してください。</p>
            <br>
        </div>

    @else

        <div class="editing_box" id="editingText">
            <br>
            <p class="mainValue {{$edit_textbox_case->value}}">{!!$edit_textbox->replace_main_value!!}</p>
            <br>
        </div>

    @endif


    <!-- editingLink -->
    @if ( !(isset($edit_textbox_case)&&($edit_textbox_case->group === 'link')) )

        <div class="editing_box hidden" id="editingLink">
            <a class="" href="#">※リンクを入力してください</a>
        </div>

    @else

        <div class="editing_box {{$edit_textbox_case->value}}" id="editingLink">
            <a href="{{$textbox->main_value}}">{{$textbox->sub_value}}</a>
        </div>

    @endif




    <!-- editingImage -->
    @if ( !(isset($edit_textbox_case)&&($edit_textbox_case->group === 'image')) )
        <div class="editing_box hidden" id="editingImage">
            <img id="previewImage">
            <p class="subValue title">※画像タイトルを入力してください。</p>
        </div>
    @else
        <div class="editing_box {{$edit_textbox_case->value}}" id="editingImage">
            {{-- <img id="previewImage" src="{{ asset('storage/'.$textbox->main_value) }}" alt=""> --}}
            <img id="previewImage" src="{{ $textbox->image_url }}" alt="">
            <p class="subValue title">{{$textbox->sub_value}}</p>
        </div>
    @endif


</div>
