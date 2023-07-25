<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Exception;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralFunctionsModuleCRMController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use MongoDB\BSON\ObjectID;
class ConfigController extends Controller
{

    public function index()
    {
        try {

            $operation = Config::all();

            $configIntegrationManager = (new IntegrationCRMController)->getOnlyConfigurationManager()->original;

            if (isset($configIntegrationManager)) {
                $dataToIntegration = $configIntegrationManager['formIntegration'];
            } else {
                $dataToIntegration = (object)[];
            }

            return response()->json(['result' => 'ok','data' => $operation, 'configIntegration' => $dataToIntegration], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => $ex->getMessage()]);
        }
    }

    public function store(Request $request, $languaje = 'en')
    {
        try {

            $config = new Config;
            $config->import = false;
            $config->lastExport = '';
            $config->company = '';
            $config->languaje = $languaje;
            $config->save();

            return response()->json(['result' => 'created'], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => $ex->getMessage()]);
        }
    }


    public function listSharedOperation(Request $request) //module user nit session
    {
        try {

            $config = new Config;

            $responseModule = Config::all();

            if ($responseModule[0] !== NULL) {
                $responseModule[0]['wolkvox_id'] = (string)$responseModule[0]['_id'];
                return response()->json(['result' => 'ok', 'data' => $responseModule], 200);
            } else {
                return response()->json(['result' => 'ok', 'data' => ''], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['result' => $ex->getMessage()]);
        }
    }

    public function addOperation(Request $request)
    {
        try {
            $config = new Config;
            $responseModule = Config::all();
            $userEmail = Session::get('user')->email;
            if ($responseModule[0]['_id'] != '') {
                Config::where('_id', $responseModule[0]['_id'])->update(['sharedOperations' => $request->sharedOperations]);
                $gf = (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'edit', 'sharedOperation', 'Edited operations ' . $responseModule[0]['_id']);
            } else {
                Config::create(['sharedOperations' => $request->sharedOperations]);
                $gf = (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'add', 'config', 'Added new operation');
            }
            return response()->json(['result' => 'ok'], 200);
        } catch (Exception $ex) {
            return response()->json(['result' => $ex->getMessage()]);
        }
    }

    public function addConfigMailgun(Request $request)
    {
        try {
            if (empty($request->data["id"])) {

                $data=[
                    "key" => Crypt::encryptString($request->data["mailgumConfiguration"]["key"]),
                    "domain" => $request->data["mailgumConfiguration"]["domain"]
                ];
                $config= new Config;
                $config->mailgumConfiguration = $data;
                $config->save();
                return response()->json(['result' => 'ok', 'data' => $config->id], 201);

            }else{
            
            $data = [
                "mailgumConfiguration" => [
                    "key" => Crypt::encryptString($request->data["mailgumConfiguration"]["key"]),
                    "domain" => $request->data["mailgumConfiguration"]["domain"]
                ]
            ];
            $userEmail = Session::get('user')->email;
            Config::where('_id', $request->data["id"])->update($data);
            $gf = (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'edit', 'config', 'Edited mailgun credentials ' .  $request->data["id"]);
            return response()->json(['result' => 'ok', 'data' => $request->_id], 201);
        }

        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }
    public function addConfigSmtp(Request $request)
    {

        try {
            if (empty($request->data["id"])) {

                $data=[
                    "server" => $request->data["smtpConfiguration"]["server"],
                    "mail" => $request->data["smtpConfiguration"]["mail"],
                    "password" => Crypt::encryptString($request->data["smtpConfiguration"]["password"])
                ];
                $config= new Config;
                $config->smtpConfiguration = $data;
                $config->save();
                return response()->json(['result' => 'ok', 'data' => $config->id], 201);
            }else{
                $data = [
                    "smtpConfiguration" => [
                        "server" => $request->data["smtpConfiguration"]["server"],
                        "mail" => $request->data["smtpConfiguration"]["mail"],
                        "password" => Crypt::encryptString($request->data["smtpConfiguration"]["password"])
                    ]
                ];
                $userEmail = Session::get('user')->email;
                Config::where('_id', $request->data["id"])->update($data);
                $gf = (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'edit', 'config', 'Edited mailgun credentials ' . $request->data["id"]);
                return response()->json(['result' => 'ok', 'data' => $request->_id], 201);


            }


        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }

    public function addSchedule(Request $request)
    {
        try {
            $data = [
                "scheduleConfiguration" => [
                    "workDays" => $request->scheduleConfiguration["workDays"],
                    "holidays" => $request->scheduleConfiguration["holidays"],
                    "data" => $request->scheduleConfiguration["data"],
                ]
            ];
            $userEmail = Session::get('user')->email;
            Config::where('_id', $request->id)->update($data);
            $gf = (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'edit', 'schedule', 'Edited record ' . $request->id);
            return response()->json(['result' => 'ok', 'data' => $request->id], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }

    public function addCurrency(Request $request)
    {
        try {
            if (empty($request->id)) {

                if ($request->type == 'local') {
                    $config= new Config;
                    $config->currencyLocal =$request->data;
                  $config->save();
                } else if ($request->type == 'global') {
                    $config= new Config;
                    $config->currencyGlobal =$request->data;
                  $config->save();

                } else {
                    return response()->json(['result' => 'ok', 'message' => 'no insert data'], 201);
                }
                $userEmail = Session::get('user')->email;
                $gf = (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'edit', 'currency', 'Created record ' . $config->id);
                return response()->json(['result' => 'ok', 'data' => $config->id], 201);

            }else{

            if ($request->type == 'local') {
                $data = [
                    "currencyLocal" => $request->data
                ];
            } elseif ($request->type == 'global') {
                $data = [
                    "currencyGlobal" => $request->data
                ];
            } else {
                return response()->json(['result' => 'ok', 'message' => 'no insert data'], 201);
            }

            $userEmail = Session::get('user')->email;
            Config::where('_id', $request->id)->update($data);
            $gf = (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'edit', 'currency', 'Edited record ' . $request->id);
            return response()->json(['result' => 'ok', 'data' => $request->id], 201);
        }


        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }

    public function addLanguaje(Request $request)
    {
        try {
            
            if ($request->id == '') {
                $saveL = $this->store($request, $request->data);
            } else {
                Config::where('_id', $request->id)->update(['languaje'=>$request->data]);
            }
            
            
            $userEmail = Session::get('user')->email;
            (new GeneralFunctionsModuleCRMController)->storeLog($userEmail, 'edit', 'Languaje', 'Edited record ' . $request->id);
            return response()->json(['result' => 'ok', 'data' => $request->id], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }
    public function predeterminedReports(Request $request)
    {

            $data = array();

            $data['email_enabled']=$request->data["status"];
            DB::collection("wolkvox_modules")
            ->where('_id',$request->data["id"])
            ->update($data, ['upsert' => true]);
            return response()->json(['result' => 'ok', 'data' =>$request->data["id"]], 201);
    }

    public function uploadFileField(Request $request)
    {
        try {
            $files = request()->allFiles();
            if (!isset($files['file'])) {
                return response()->json(['result' => 'error', 'error' => 'no files were found'], 500);
            }

            $fileIdList = [];

            foreach ($files['file'] as $key => $value) {
                $bucket = DB::connection('mongodb')->selectGridFSBucket();
                $file = fopen($value, 'rb');
                $fileId = $bucket->uploadFromStream($value->getClientOriginalName(), $file);
                $fileIdList[] = ['id'=>(string)$fileId,'name'=>$value->getClientOriginalName()];
            }

            return response()->json(['result' => 'ok', 'filesSaved' => $fileIdList], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'error' => $ex->getMessage()], 500);
        }
    }
    
    public function downloadFileField($fileId)
    {
        try {
            $fileId = new \MongoDB\BSON\ObjectID($fileId);
            $bucket = DB::connection('mongodb')->selectGridFSBucket();
            $stream = $bucket->openDownloadStream($fileId);
            $metadata = $bucket->getFileDocumentForStream($stream);

            $contents = stream_get_contents($stream);
            $filename = preg_replace("/[^a-zA-Z0-9.]/", "", $metadata['filename']);
            $size = (int)$metadata['length'];

            $headers = [
                'Content-Description' => 'File Transfer',
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename=' . $filename,
                'Content-Transfer-Encoding' => 'binary',
                'Expires' => '0',
                'Cache-Control' => 'must-revalidate',
                'Pragma' => 'public',
                'Content-Length' => $size,
            ];

            return Response::make($contents, 200, $headers);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'error' => $ex->getMessage()], 500);
        }
    }
    
    public function deleteFileField($fileId)
    {
        try {
            $file = DB::collection('fs.files')->where('_id', $fileId)->first();
            if (!$file) {
                return false;
            }
            DB::collection('fs.files')->where('_id', $fileId)->delete();
            DB::collection('fs.chunks')->where('files_id', $fileId)->delete();
            return response()->json(['result' => 'ok'], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'error' => $ex->getMessage()], 500);
        }
    }
    
}