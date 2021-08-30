<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNoteFomeRequest extends FormRequest
{
    public function authorize(){ return true;}


    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        $lules = [
            'title' =>'max:100',
            'new_tags' =>'max:100',
            'main_image'=>'file|max:1600|mimes:jpeg,png,jpg,pdf',
        ];

        // 新しいタグが追加されなかった時、バリデーションを追加
        $request = $this->all();
        if($request['new_tags']==null)
        {
            $lules['tags'] = 'required';
        }

        return $lules;
    }


    /**
     * バリデーションエラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.max' =>'タイトルは100文字以内になるように入力してください。',
            'new_tags.max' =>'タグは合計文字数が100文字以内になるように入力してください。',
            'tags.required' => '一つ以上タグを選択してください。',
            'main_image.max' => '1.6MBを超えるファイルは添付できません。',
            'main_image.mims' => '指定のファイル形式以外は添付できません。'
        ];
    }

}
