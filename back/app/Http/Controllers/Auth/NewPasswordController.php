<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            
            $request->validate([
                'password_new' => ['required', 'confirmed', Rules\Password::min(8)->letters()->mixedCase()->symbols() ],
            ]);

            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {

                $request->session()->regenerate();
                $id = Auth::id();
                User::where('_id', $id)->update(['password' => Hash::make($request->password_new)]);
                return response()->json(['result' => 'ok'], 201);
            } else {
                return response()->json(['result' => 'Unauthorized'], 401);
            }
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage() , 'line' => $ex->getLine() ]);
        }
    }

    public function update(Request $request)
    {
        try {
            Log::info(Session::get('operation'));
            $pass = Hash::make(Session::get('operation'));
            Log::info($pass);
            User::where('_id', $request->id)->update(['password' => $pass]);

            return response()->json(['result' => 'ok']);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }
}
