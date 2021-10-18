<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTextboxFormRequest extends FormRequest
{
    public function authorize(){ return true;}


    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        # 画像のアップロードがあるとき
        $request = $this->all();
        if( isset($request['image']) && !isset($request['old_image']) )
        {
            $rules['image'] = 'file|max:1600|mimes:jpeg,png,jpg';
        }

        return $rules;
    }


    /**
     * エラーメッセージの設定
     *
     * @return array
     */
    public function messages()
    {
        return [
            'image.max' => '1.6MBを超えるファイルは添付できません。',
            'image.mimes' => '添付画像のファイル形式は、jpeg、png、ipg以外では保存できません。',
        ];
    }
}
