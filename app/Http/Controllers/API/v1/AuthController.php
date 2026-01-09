<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\FCMToken;
use App\Models\User;
use App\Traits\generateAPI;
use App\Traits\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    use generateAPI;
    use Token;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login', 'forgotPassword');
    }

    public function login(Request $request){
//        $user = Auth::attempt($request->only('phonenumber', 'password'));

        $user = false;

        if (is_numeric($request->username)){
            $user = Auth::attempt(['phonenumber' => $request->username, 'password' => $request->password]);
        }else if (filter_var($request->username, FILTER_VALIDATE_EMAIL)){
            $user = Auth::attempt(['email' => $request->username, 'password' => $request->password]);
        }

        if ($user){
            $token = Auth::user()->createToken('myAppToken')->plainTextToken;
            return $this->success($this->respondWithToken($token));
        }else
            return response()->json('برجاء التأكد من البيانات المرسلة.');
    }

    public function me()
    {
        $user = auth()->user();

        return $this->success([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
//        auth()->logout();
        auth()->user()->tokens()->delete();
        return $this->success([], 'تم تسجيل الخروج بنجاح');
    }


    public function refresh()
    {
        $token = auth()->user()->createToken('myAppToken')->plainTextToken;
        return $this->success($this->respondWithToken($token));
    }

    public function forgotPassword(Request $request){

        $validate = Validator::make($request->all(), [
            'email' => 'email|required'
        ]);

        if ($validate->fails())
            return $this->error($validate->errors(), '', 500);

        $user = User::find($request->email);

        if (!is_null($user))
            return $this->success([], 'برجاء مراجعة البريد الإلكتروني و اتباع الخطوات.');

        return $this->error([], 'هذا البريد الإلكتروني غير مسجل في الموقع.');
    }

    public function update(Request $request){
        $user = User::find(16);

        $data = [
            'first_name' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id),
                'email'
            ],
            'phone' => [
                'required',
                Rule::unique('users')->ignore($user->id),
                'digits:11'
            ],
            'password' => 'nullable|min:8|confirmed'
        ];

        $validate = Validator::make($request->all(), $data);

        if ($validate->fails())
            return $this->error($validate->errors(), '');

        $user->name = $request->first_name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->has('password'))
            $user->password = Hash::make($request->password);

        if ($user->save())
            return $this->success([], 'تم تعديل البيانات بنجاح.');

        else
            return $this->error([], 'هناك مشكلة في تعديل البيانات ، برجاء التواصل مع الدعم الفني.');
    }

    public function fcm(Request $request){
        $request->validate([
            'fcm_token' => 'required'
        ]);

//        $update = User::findOrFail(auth()->id())->update([
//            'fcm_token' => $request->fcm_token
//        ]);

        FCMToken::where('token', $request->fcm_token)->delete();

        $update = FCMToken::create([
            'user_id' => auth()->id(),
            'token' => $request->fcm_token,
            'app_name' => 'durrah',
            'app_type' => 'mobile'
        ]);

        if ($update)
            return $this->success([], 'تم التعديل بنجاح.');

        return $this->error([], 'هناك مشكلة في تعديل البيانات ، برجاء التواصل مع الدعم الفني.');
    }
}
