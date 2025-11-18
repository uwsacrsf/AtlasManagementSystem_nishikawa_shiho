<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use DB;

use App\Models\Users\Subjects;
use App\Models\Users\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
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
         ],[
            // 1. 姓・名（漢字）
            'over_name.required'    => '姓は必須項目です。',
            'over_name.max'         => '姓は10文字以内で入力してください。',
            'under_name.required'   => '名は必須項目です。',
            'under_name.max'        => '名は10文字以内で入力してください。',

            // 2. 姓・名（カナ）
            'over_name_kana.required' => 'セイは必須項目です。',
            'over_name_kana.max'      => 'セイは30文字以内で入力してください。',
            'over_name_kana.regex'    => 'セイは全角カタカナで入力してください。',
            'under_name_kana.required'=> 'メイは必須項目です。',
            'under_name_kana.max'     => 'メイは30文字以内で入力してください。',
            'under_name_kana.regex'   => 'メイは全角カタカナで入力してください。',

            // 3. メールアドレス
            'mail_address.required' => 'メールアドレスは必須項目です。',
            'mail_address.email'    => '※メール形式で入力してください',
            'mail_address.max'      => 'メールアドレスは100文字以内で入力してください。',
            'mail_address.unique'   => 'このメールアドレスは既に登録されています。',

            // 4. 性別
            'sex.required'          => '性別は必須項目です。',
            'sex.in'                => '選択された性別の値が不正です。',

            // 5. 生年月日
            'old_year.required'     => '生年月日の年を選択してください。',
            'old_month.required'    => '生年月日の月を選択してください。',
            'old_day.required'      => '生年月日の日を選択してください。',
            'birth_day.required'    => '生年月日が入力必須です。',
            'birth_day.date'        => '※正しい日付形式で入力してください。',
            'birth_day.after_or_equal' => '生年月日は2000年1月1日以降を選択してください。',
            'birth_day.before_or_equal' => '生年月日は今日以前の日付を選択してください。',

            // 6. 権限（ロール）
            'role.required'         => '権限は必須項目です。',
            'role.in'               => '選択された権限の値が不正です。',

            // 7. パスワード
            'password.required'     => 'パスワードは必須項目です。',
            'password.string'       => 'パスワードは文字列で入力してください。',
            'password.min'          => 'パスワードは8文字以上で入力してください。',
            'password.max'          => 'パスワードは30文字以下で入力してください。',
            'password.confirmed'    => '確認用パスワードと一致しません。',
            ]);

        DB::beginTransaction();
        try{
            $old_year = $request->old_year;
            $old_month = $request->old_month;
            $old_day = $request->old_day;
            $data = $old_year . '-' . $old_month . '-' . $old_day;
            $birth_day = date('Y-m-d', strtotime($data));
            $subjects = $request->subject;

            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);
            if($request->role == 4){
                $user = User::findOrFail($user_get->id);
                $user->subjects()->attach($subjects);
            }
            DB::commit();
            return view('auth.login.login');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->route('loginView');
        }
    }
}
