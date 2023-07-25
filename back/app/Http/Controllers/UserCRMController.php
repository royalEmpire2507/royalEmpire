<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use MongoDB\BSON\UTCDateTime;

class UserCRMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = User::paginate(500);
            return response()->json(['result' => 'ok','content'=>$data], 201);
        }catch (Exception $ex){
            return response()->json(['result' => 'error','data'=>$ex->getMessage()], 201);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class]
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'rol' => 'client',
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['result' => 'ok','user'=>$user->id], 201);
    }

    public function userLogged(Request $request)
    {
        try {
            $data = Auth::user();
            return response()->json(['result' => 'ok','content'=>$data], 201);
        }catch (Exception $ex){
            return response()->json(['result' => 'error','data'=>$ex->getMessage()], 201);
        }
    }
    
    public function destroy(Request $request, User $user)
    {
        try {
            User::where('_id', $request->module['_id'])->delete();
            return response()->json(['result' => 'ok'], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error','module'=>$ex->getMessage()]);
        }
    }

    public function editSMTPCredentials(Request $request, User $user){
        try {
            $credentials = [
                'smtpPassword'=>Crypt::encryptString($request->smtpPassword),
                'smtpServer'=>$request->smtpServer,
                'smtpMail'=>$request->smtpMail,
            ];

            $toUpdate = [
                'smtpActive'=>$request->smtpCrecentials,
                'smtpCrecentials'=>$credentials

            ];
            $id = Auth::id();
            User::where('_id', $id)->update($toUpdate);
            return response()->json(['result' => 'ok'], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error','module'=>$ex->getMessage()]);
        }
    }

    public function editLastConection()
    {
        $usuario = Session::get('user');
        $ultimaConexion = $usuario->wolkvox_last_connection;
        $diffMinutos = '';
        if($ultimaConexion !== null && $ultimaConexion !== ''){
            $ucFormat = (
                new \DateTime(
                    $usuario->wolkvox_last_connection->toDateTime()->format('Y-m-d H:i:s.u'),
                    new \DateTimeZone('UTC'))
                )->format('Y-m-d H:i:s');
                        
            $fecha1 = Carbon::now();
            $fecha2 = Carbon::parse($ucFormat);
    
            $diffMinutos = $fecha1->diffInMinutes($fecha2);
        }

        if ($ultimaConexion === null || $ultimaConexion === '' || $diffMinutos >= 15) {
            Log::info("edito fecha pasados ".$diffMinutos." min., colocó ".Carbon::now());
            $id = Auth::id();
            User::where('_id', $id)
                ->update(
                    ['wolkvox_last_connection'=>new UTCDateTime(Carbon::now()->timestamp * 1000)]
                );
        }
    }
}
