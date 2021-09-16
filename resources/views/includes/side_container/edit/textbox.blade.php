<h5><i class="bi bi-pencil-fill"></i> テキストボックスの入力</h5>




<div class="input_group_container mb-6">


    <div class="form_group mb-4">
        <label class="fw-bold text-primary" for="input_age_group">テキストボックスの種類を選択してください。</label>
        <select class="form-control fs-3 text-primary fw-bold" name="age_group" id="inputTextBoxType">
            <option value="">-- 選択 --</option>
            <option value="heading1">見出し1</option>
            <option value="heading2">見出し2</option>
            <option value="heading3">見出し3</option>

            <option value="normalText">通常の文章</option>
            <option value="importantText">重要な文章</option>
            <option value="emphasizedText">強調する文章</option>
            <option value=quoteText"">引用文</option>
            <option value="codeText">コード文</option>

            <option value="link">リンク</option>
            <option value="image">大きい画像</option>
            <option value="image_litle">小さい画像</option>
        </select>
    </div>








    <form action="" class="input_box hidden" id="heading1">


        <input type="hidden" name="textbox_cases_id" val="1">

        <div class="form_group mb-4">
            <label class="form-label" for="">見出しを入力して下さい。</label>
            <textarea name="text" class="form-control" style="height: 4rem;"
                placeholder="※重要な言葉を {{ '{'.'{' }} と {{ '}'.'}' }} (半角記号) で囲むと強調されます。" id="" ></textarea>
            <!-- <p style="color:red;margin-top:.5em;">エラーメッセージ</p> -->
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold" for=""></label>
            <input type="hidden" name="subval" class="form-control" value="" placeholder="" id="">
            <!-- <p style="color:red;margin-top:.5em;">エラーメッセージ</p> -->
        </div>


        <div class="form_group d-grid gap-2">
            <button type="button" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
        </div>


    </form>








    <form action="" class="input_box hidden" id="link">


        <input type="hidden" name="textbox_cases_id" val="1">

        <div class="form_group mb-4">
            <label class="form-label" for="">リンク名を入力してください。</label>
            <textarea name="text" class="form-control" style="height: 4rem;" placeholder="リンクタイトル" id="" ></textarea>
            <!-- <p style="color:red;margin-top:.5em;">エラーメッセージ</p> -->
        </div>

        <div class="form_group mb-4">
            <label class="fw-bold" for="">リンク先のURLを入力してください。</label>
            <input type="text" name="subval" class="form-control" placeholder="半角記号英数字" id="">
            <!-- <p style="color:red;margin-top:.5em;">エラーメッセージ</p> -->
        </div>


        <div class="form_group d-grid gap-2">
            <button type="button" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
        </div>


    </form>








    <form action="" class="input_box hidden" id="image">


        <input type="hidden" name="textbox_cases_id" val="1">


        <div class="form_group mb-4 hidden"> <!--(非表示)-->
            <label class="form-label" for=""></label>
            <textarea name="text" class="form-control" style="height: 4rem;" placeholder="" id="" ></textarea>
        </div>

        <div class="form_group mb-4">
            <label class="form-label" for="">挿入する画像を選択してください。</label>
            <input type="file" name="image" class="form-control" onchange="setImage(this);" onclick="this.value = '';" id="fileImage">
            <!-- <p style="color:red;margin-top:.5em;">エラーメッセージ</p> -->
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold" for="">画像のタイトルを選択してください。</label>
            <input type="text" name="subval" class="form-control" placeholder="画像のタイトル">
            <!-- <p style="color:red;margin-top:.5em;">エラーメッセージ</p> -->
        </div>


        <div class="form_group d-grid gap-2">
            <button type="button" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
        </div>


    </form>




</div>

