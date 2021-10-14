<h5><i class="bi bi-pencil-fill"></i> テキストボックスの入力</h5>




<div class="input_group_container mb-6">

    <!-- エラー表示 -->
    @if ($errors->all())
        @foreach ($errors->all() as $error)
        <div class="mb-2" style="color:red;">{{$error}}</div>
        @endforeach
    @endif

    <div class="form_group mb-5">
        <label class="fw-bold text-primary" for="inputTextBoxCase">テキストボックスの種類を選択してください。</label>
        <select class="form-control fs-3 text-primary fw-bold" name="age_group" id="inputTextBoxCase">
            <option value="">-- 選択 --</option>

            @foreach ($select_textbox_cases as $item)

                @if ( !isset($textbox) ) <!-- ( create ) -->
                    <option value="{{$item->value}}">{{$item->text}}</option>

                @else <!-- ( edit ) -->
                    <option value="{{$item->value}}" {{$item->value===$textbox_case->value? 'selected': ''}}>
                        {{$item->text}}
                    </option>
                @endif

            @endforeach

        </select>
    </div>





    <!-- inputHeading -->
    @if ( !isset($textbox) ) <!-- ( create ) -->
    <form method="POST" action="{{route('store_textbox',$note)}}" class="input_box hidden" id="inputHeading">
        @csrf
    @else <!-- ( edit ) -->
    <form method="POST" action="{{route('update_textbox',$note)}}" id="inputHeading"
        class="input_box {{$textbox_case->group === 'heading'? '': 'hidden'}}">
        @method('PATCH')
        @csrf
    @endif

        <input type="hidden" name="order" value="{{$order}}">
        <input type="hidden" name="textbox_case_name" value="{{isset($textbox)? $textbox_case->value:''}}">

        <div class="form_group mb-5">

            <label class="fw-bold" for="inputHeadingMainValue">見出しを入力して下さい。</label>
            <input type="text" name="main_value" class="form-control"
            placeholder="※重要な言葉は {{ '{'.'{' }} と {{ '}'.'}' }} (半角記号) で囲む。" id="inputHeadingMainValue" required
            value="{{ isset($textbox)? $textbox->main_value: '' }}">

        </div>


        <div class="form_group d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
        </div>

    </form>





    <!-- inputText -->
    <form method="POST" action="{{route('store_textbox',$note)}}" class="input_box hidden" id="inputText">
        @csrf
        <input type="hidden" name="order" value="{{$order}}">
        <input type="hidden" name="textbox_case_name" value="">

        <div class="form_group mb-5">
            <label class="fw-bold" for="inputTextMainValue">文章を入力して下さい。</label>
            <textarea name="main_value" class="form-control" style="height:12rem;"
                placeholder="※重要な言葉は {{ '{'.'{' }} と {{ '}'.'}' }} (半角記号) で囲む。" id="inputTextMainValue" required></textarea>
        </div>


        <div class="form_group d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
        </div>
    </form>








    <!-- inputLink -->
    <form method="POST" action="{{route('store_textbox',$note)}}" class="input_box hidden" id="inputLink">
        @csrf
        <input type="hidden" name="order" value="{{$order}}">
        <input type="hidden" name="textbox_case_name" value="">

        <div class="form_group mb-5">
            <label class="fw-bold" for="inputLinkMainValue">リンク先URLを入力して下さい。</label>
            <input type="text" name="main_value" class="form-control" placeholder="半角記号英数字" id="inputLinkMainValue" required>
        </div>

        <div class="form_group mb-5">
            <label class="fw-bold" for="inputLinkSubValue">リンクタイトルを入力してください。</label>
            <input type="text" name="sub_value" class="form-control" placeholder="リンクタイトル" id="inputLinkSubValue" required>
        </div>

        <div class="form_group d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
        </div>
    </form>







    <!-- inputImage -->
    <form method="POST" action="{{route('store_textbox',$note)}}" class="input_box hidden" id="inputImage" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order" value="{{$order}}">
        <input type="hidden" name="textbox_case_name" value="">

        <div class="form_group mb-5">
            <label class="form-label" for="fileImage">挿入する画像を選択してください。</label>
            <input type="file" name="image" class="form-control" onchange="setImage(this);" id="fileImage" required>
        </div>

        <div class="form_group mb-5">
            <label class="fw-bold" for="inputImageSubValue">画像タイトルを入力してください。</label>
            <input type="text" name="sub_value" class="form-control" placeholder="画像のタイトル" id="inputImageSubValue" required>
        </div>

        <div class="form_group d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
        </div>
    </form>


</div>

