<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNoteFomeRequest extends FormRequest
{
    public function authorize(){ return true;}

    public function rules()
    {
        $lules = [
            'title' =>'max:100',
            'new_hashtags' =>'max:100',
        ];

        $request = $this->all();
        if($request['new_hashtags']==null)
        {
            $lules['hashtags'] = 'required';
        }

        return $lules;
    }

    public function messages()
    {
        return [
            'title.max' =>'タイトルは100文字以内になるように入力してください。',
            'new_hashtags.max' =>'タグは合計文字数が100文字以内になるように入力してください。',
            'hashtags.required' => '一つ以上タグを選択してください。',
        ];
    }

}
