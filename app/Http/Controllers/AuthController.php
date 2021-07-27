<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password2' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return $this->ErrorRespone('validasi error' , $validator->errors());
        }

        $checkemail = User::where("email",$input['email'])->first();
        if ($checkemail) {
            return $this->ErrorRespone('email already');
        }

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $content = [
            'token' => $user->createToken('pilput')->plainTextToken,
            'name' => $user->name,
            'email' => $user->email,
        ];

        return $this->SuccessRespone($content, "user created");



    }
    public function login(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->ErrorRespone('validasi error' , $validator->errors());
        }
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            $content = [
                'token' => $user->createToken('pilput')->plainTextToken,
                'name' => $user->name,
                'email' => $user->email,
            ];
            return $this->SuccessRespone($content,'berhasil login');
        }else{
            return $this->ErrorRespone('gagal login');
        }
    }
}
