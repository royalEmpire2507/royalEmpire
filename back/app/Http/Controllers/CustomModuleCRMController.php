<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GeneralFunctionsModuleCRMController;
use App\Models\CustomModuleCRM;
use App\Models\User;
use App\Http\Controllers\ActionFormController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Exception;
use Mail;
use App\Mail\SendEmails;

use MongoDB\BSON\UTCDateTime;

class CustomModuleCRMController extends Controller
{

    public function index($pageSize)
    {

        $data = User::where('rol', 'client')->orderBy('created_at', 'desc')
            ->paginate($pageSize)->toArray();

        return response()->json(['result' => 'ok', 'content' => $data], 201);
    }

    public function store(Request $request)
    {

        $data = [];

        $data['created_at'] = new UTCDateTime(Carbon::now()->timestamp * 1000);
        $data['updated_at'] = new UTCDateTime(Carbon::now()->timestamp * 1000);

        foreach ($request->data as $key => $value) {
            $data[$key] = !empty($value) ? $value : '';
        }

        $custom = DB::connection('mongodb')
            ->collection($request->module)
            ->insertGetId($data);

        return response()->json(['result' => 'ok']);
    }

    public function show(Request $request)
    {
        try {
            $data = DB::collection($request->collection)
                ->where('_id', $request->id)
                ->get();

            return response()->json(['result' => 'ok', 'content' => $data[0]], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'data' => $ex->getMessage()], 201);
        }
    }

    public function update(Request $request)
    {
        try {

            $data = array();
            $data['updated_at'] = new UTCDateTime(Carbon::now()->timestamp * 1000);
            $data['amount'] = $request->amount;
            $data['email'] = $request->email;
            $data['firstname'] = $request->firstname;
            $data['lastname'] = $request->lastname;
            $data['phone'] = $request->phone;

            DB::collection('users')
                ->where('_id', $request->_id)
                ->update($data, ['upsert' => true]);

            return response()->json(
                ['result' => 'ok', 'data' => $request->_id],
                201
            );
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }

    public function destroy(Request $request, CustomModuleCRM $customModuleCRM)
    {
        try {
            $userEmail = Session::get('user')->email;
            foreach ($request->records as $key => $value) {
                DB::collection($request->collection)
                    ->where('_id', $value['_id'])
                    ->delete();

                (new GeneralFunctionsModuleCRMController)
                    ->storeLog($userEmail, 'delete', $request->collection, 'Deleted record' . $value['_id']);
            }

            return response()->json(['result' => 'ok'], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }

    public function sendMail(Request $request)
    {
        Mail::to($request->mailTo)->bcc('Support@financialdistrictfirm.com')->send(new SendEmails($request->amount));

        if (Mail::flushMacros()) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        } else {
            return response()->json(['result' => 'ok'], 201);
        }
    }
}
