<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'city' => $request->city,
            'direction' => $request->direction,
            'doc' => $request->doc,
            'levelUser' => $request->levelUser,
            'license' => $request->license,
            'phone' => $request->phone,
            'rol' => $request->rol,
            'profile' => $request->profile,
            'userEmail' => $request->userEmail,
            'password' => Hash::make($request->operation),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // return response()->noContent();
        return response()->json(['result' => 'ok','user'=>$user->id], 201);
    }

    public function update(Request $request, User $user)
    {
        try {
            $user = array();
            $user['doc'] = $request->doc;
            $user['name'] = $request->name;
            $user['userEmail'] = $request->userEmail;
            $user['phone'] = $request->phone;
            $user['city'] = $request->city;
            $user['direction'] = $request->direction;
            $user['rol'] = $request->rol;
            $user['profile'] = $request->profile;
            $user['levelUser'] = $request->levelUser;
            $user['license'] = $request->license;
            User::where('_id', $request->_id)->update($user);

            return response()->json(['result' => 'ok','data'=>$request->_id], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error','module'=>$ex->getMessage()]);
        }
    }
}
