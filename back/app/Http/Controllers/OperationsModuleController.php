<?php

namespace App\Http\Controllers;

use App\Models\OperationsModule;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\GeneralFunctionsModuleCRMController;
use App\Http\Controllers\ActionFormController;
use Illuminate\Support\Facades\Session;

class OperationsModuleController extends Controller
{

    public function index($idClient)
    {
        try {
            $data = OperationsModule::where('idRecord', $idClient)->get();
            return response()->json(['result' => 'ok', 'content' => $data], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'data' => $ex->getMessage()], 201);
        }
    }

    // public function store(Request $request)
    // {
    //     try {

    //         $newOp = new OperationsModule();
    //         foreach ($request->data as $key => $value) {
    //             $newOp->{$key} = !empty($value) ? $value : '';
    //         }
    //         $newOp->save();
            

    //         return response()->json(['result' => 'ok', 'idRecord' => $newOp->id], 201);
    //     } catch (Exception $ex) {
    //         return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
    //     }
    // }

    public function show($idReg)
    {
        try {
            $data = OperationsModule::where("_id", $idReg)->get();
            return response()->json(['result' => 'ok', 'content' => $data], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'data' => $ex->getMessage()], 201);
        }
    }

    public function update(Request $request)
    {
        try {

            $data = array();
            $data['price_order'] = $request->price_order;
            $data['price_actual'] = $request->price_actual;
            $data['open_date'] = $request->open_date;
            $data['status'] = $request->status;
            $data['direction'] = $request->direction;
            $data['initial_cap'] = $request->initial_cap;
            
            OperationsModule::where("_id", $request->_id)->update($data);

            return response()->json(
                ['result' => 'ok', 'id' => $request->_id],
                201
            );
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }

    // public function destroy(Request $request)
    // {
    //     try {
    //         $userEmail = Session::get('user')->email;
    //         foreach ($request->records as $value) {
    //             OperationsModule::where('_id', $value['_id'])->delete();
    //             (new GeneralFunctionsModuleCRMController)
    //             ->storeLog($userEmail, 'delete', 'contacts', 'Deleted record' . $value['_id']);
    //         }
    //         return response()->json(['result' => 'ok'], 201);
    //     } catch (Exception $ex) {
    //         return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
    //     }
    // }
}
