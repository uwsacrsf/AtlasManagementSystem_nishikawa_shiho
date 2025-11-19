<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'over_name' => ['required', 'string', 'max:10'],
            'under_name' => ['required', 'string', 'max:10'],
            'over_name_kana' => ['required', 'string', 'max: 30', 'regex:/^([ァ-ヴ]+)$/u'],
            'under_name_kana' => ['required', 'string', 'max: 30', 'regex:/^([ァ-ヴ]+)$/u'],
            'mail_address' => ['required', 'string', 'email', 'max:100', 'unique:users,mail_address'],
            'sex' => ['required', 'in:1,2,3'],
            'old_year'=> ['required', 'numeric'],
            'old_month'=> ['required', 'numeric'],
            'old_day'=> ['required', 'numeric'],
            'role' => ['required', 'in:1,2,3,4,5'],
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed'],
        ];
    }

    /**
     *
     * @return array
     */
    public function messages()
    {
        return [
            'over_name.required'    => '姓は必須項目です。',
            'over_name.max'         => '姓は10文字以内で入力してください。',
            'under_name.required'   => '名は必須項目です。',
            'under_name.max'        => '名は10文字以内で入力してください。',

            'over_name_kana.required' => 'セイは必須項目です。',
            'over_name_kana.max'      => 'セイは30文字以内で入力してください。',
            'over_name_kana.regex'    => 'セイは全角カタカナで入力してください。',
            'under_name_kana.required'=> 'メイは必須項目です。',
            'under_name_kana.max'     => 'メイは30文字以内で入力してください。',
            'under_name_kana.regex'   => 'メイは全角カタカナで入力してください。',

            'mail_address.required' => 'メールアドレスは必須項目です。',
            'mail_address.email'    => '※メール形式で入力してください',
            'mail_address.max'      => 'メールアドレスは100文字以内で入力してください。',
            'mail_address.unique'   => 'このメールアドレスは既に登録されています。',

            'sex.required'          => '性別は必須項目です。',
            'sex.in'                => '選択された性別の値が不正です。',

            'old_year.required'     => '生年月日の年を選択してください。',
            'old_month.required'    => '生年月日の月を選択してください。',
            'old_day.required'      => '生年月日の日を選択してください。',

            'role.required'         => '権限は必須項目です。',
            'role.in'               => '選択された権限の値が不正です。',

            'password.required'     => 'パスワードは必須項目です。',
            'password.string'       => 'パスワードは文字列で入力してください。',
            'password.min'          => 'パスワードは8文字以上で入力してください。',
            'password.max'          => 'パスワードは30文字以下で入力してください。',
            'password.confirmed'    => '確認用パスワードと一致しません。',
        ];
    }
}
