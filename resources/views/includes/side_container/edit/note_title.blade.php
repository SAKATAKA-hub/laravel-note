<div class="input_group_container">


    <form action="">


        <div class="form_group mb-4">
            <label class="fw-bold" for="inputNoteTitle">タイト</label>
            <input type="text" name="title" class="form-control fs-3" placeholder="タイトル" id="inputNoteTitle" required>
            <p style="color:red;margin-top:.5em;">エラーメッセージ</p>
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold" for="inputNoteColor">テーマカラー</label>
            <select class="form-control" name="color" id="inputNoteColor" required>
                <option value="green" selected>グリーン</option>
                <option value="teal">ティール</option>
                <option value="cyan">シアン</option>
                <option value="blue">ブルー</option>
                <option value="indigo">インディゴ</option>
                <option value="purple">パープル</option>
                <option value="pink">ピンク</option>
                <option value="red">レッド</option>
                <option value="orange">オレンジ</option>
                <option value="yellow">イエロー</option>
            </select>
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold">タグ</label>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tags[]" value="パソコン" id="inputNoteTag1" required>
                <label class="form-check-label" for="inputNoteTag1">
                    パソコン
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tags[]" value="タブレット" id="inputNoteTag2">
                <label class="form-check-label" for="inputNoteTag2">
                    タブレット
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tags[]" value="スマートフォン" id="inputNoteTag3">
                <label class="form-check-label" for="inputNoteTag3">
                    スマートフォン
                </label>
            </div>

            <input type="text" name="tags[]" class="form-control" placeholder="複数のタグを追加するときは、'空白文字'を挟む。 " id="newTags">


            <p style="color:red;height:1em;"></p>
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold">公開設定</label>
            <div class="form-check form-switch fs-5 mt-2">
                <input class="form-check-input" type="checkbox" id="inputPublishing"  checked="checked">
                <label class="form-check-label fs-5 fw-bold text-primary" for="inputPublishing">公開</label>
            </div>
        </div>


        <div class="form_group d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">基本設定の保存</button>
        </div>

    </form>


</div>
