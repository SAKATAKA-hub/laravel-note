<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRegisterFormRequest extends FormRequest
{
    public function authorize(){ return true;}


    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'max:255',
            'email' => 'email',
            'password' => 'regex:/\A[a-z\d]{8,255}+\z/i|confirmed',
            // 'password_confirmation' => 'required',
        ];

        # 画像のアップロードがあるとき
        $request = $this->all();
        if( isset($request['image']))
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
            'name.max' => '255文字以内で入力してください。',

            'email.email' => 'メールアドレスは、メールの記述形式になるように入力してください。',

            'password.regex' => 'パスワードは、8文字以上の半角英数字のみで入力してください。',
            'password.confirmed' => 'パスワードが異なります。',

            'image.max' => '1.6MBを超えるファイルは添付できません。',
            'image.mimes' => '添付画像のファイル形式は、jpeg、png、ipg以外では保存できません。',
        ];
    }
}
