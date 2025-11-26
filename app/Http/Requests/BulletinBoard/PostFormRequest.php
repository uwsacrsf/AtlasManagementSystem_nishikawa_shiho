<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 基本となるルール（新規投稿時に適用）
        $rules = [
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',
            // 'post_category_id' => 'required|exists:sub_categories,id', // カテゴリは新規作成時のみ必須と想定
        ];

        // 編集処理の場合 (post_id がリクエストに含まれている場合)
        if ($this->has('post_id')) {
            $rules['post_id'] = 'required|exists:posts,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'post_title.required' => 'タイトルは必ず入力してください。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '内容は必ず入力してください。',
            'post_body.max' => '内容は2000文字以内で入力してください。',

            // 編集時用のメッセージ
            'post_id.required' => '編集対象の投稿IDが指定されていません。',
            'post_id.exists' => '存在しない投稿IDです。',
        ];
    }
}
