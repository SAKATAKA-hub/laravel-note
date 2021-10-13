<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditNoteTitleFormRequest extends FormRequest
{
    public function authorize(){ return true;}


    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        # バリデーションルール
        $rules = [
            'title' => 'required',
            'color' => 'required',
            'tags' => 'required|array',
            // 'tags.*' => 'required',
        ];

        # タグに選択がない時、バリデーションを追加
        $request = $this->all();
        if( count($request['tags']) < 2 )
        {
            $rules['tags.*'] = 'required';
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
            'title.required' => '※タイトルを入力してください。',
            'color.required' => '※テーマカラーを選択してください。',
            'tags.0.required' => '※タグを一つ以上選択してください。',
        ];
    }
}
