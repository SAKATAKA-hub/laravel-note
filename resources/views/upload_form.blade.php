<form method="POST" action="{{route('upload_file')}}" enctype="multipart/form-data">
    @csrf
    <h2>アップロード</h2>
    <input type="file" name="upload_file"><br>
    <button>保存</button>
</form>
