<!--

    * モーダル表示ボタン
    <button class="btn btn-primary" value="" type="button" data-bs-toggle="modal" data-bs-target="#centerModal">
        modal button
    </button>


    * モーダル表示内容の指定
    (@)php
        $modal = [
            'title' => '入力内容の新規登録',
            'body' => 'この内容で新規登録します。</br>よろしいですか？',
            'yes_btn' => '登録',
        ];
    (@)endphp


    * モーダルサブビューの読み込み
    (@)include('includes.modal')


    * js読込み (deletModalInput関数)
    <script src="{ aseet('js/includes/modal.js') }}"></script>

-->




<!-- Modal -->
<div class="modal fade" id="centerModal" tabindex="-1" aria-labelledby="centerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">


            <div class="modal-header">
                <!--( title )-->
                <h5 class="modal-title" id="centerModalLabel">
                    {{ $modal['title'] }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <!--( body )-->
                {!! str_replace('\n','<br>', e($modal['body']) ) !!}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <button type="submit" class="btn btn-primary">{{ $modal['yes_btn'] }}</button>
            </div>
        </div>
    </div>
</div>



