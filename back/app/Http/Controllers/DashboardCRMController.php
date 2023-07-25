<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\DashboardCRM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ModulesCRM;
use DateTime;
use DateTimeZone;
use Exception;

class DashboardCRMController extends Controller
{

    public function index()
    {

        $module = "";

        try {
            $dashboar = DashboardCRM::get();
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'data' => $ex->getMessage()], 201);
        }

        if (count($dashboar) != 0) {

            $data = [];
            foreach ($dashboar as $key => $value) {
                $idPannel = (string)$value['_id'];
                try {
                    foreach ($value['graphics'] as $keyDash => $valueDash) {
                        if (!empty($valueDash['data'])) {
                            $module = ModulesCRM::where("_id", $valueDash['data']['module'])->get();

                            if (!empty($module)) {
                                $module = $module[0]['name'];
                                $valueDash['type'] = $valueDash['data']['charType'];

                                if (
                                    $valueDash['data']['charType'] == 'funnel' ||
                                    $valueDash['data']['charType'] == 'pie' ||
                                    $valueDash['data']['charType'] == 'bar' ||
                                    $valueDash['data']['charType'] == 'barHorizontal'
                                ) {
                                    $allData = $this->basiclListData(
                                        $module,
                                        $valueDash['data']['action'],
                                        $valueDash['data']['filterBy'],
                                        $valueDash['data']['from'],
                                        $valueDash['data']['to'],
                                        $valueDash['data']['measures'],
                                        $valueDash['data']['sources'],
                                        $valueDash['data']['argument']
                                    );
                                } elseif ($valueDash['data']['charType'] == 'line') {
                                    $allData = $this->lineListData(
                                        $module,
                                        $valueDash['data']['action'],
                                        $valueDash['data']['filterBy'],
                                        $valueDash['data']['from'],
                                        $valueDash['data']['to'],
                                        $valueDash['data']['measures'],
                                        $valueDash['data']['sources'],
                                        $valueDash['data']['argument']
                                    );
                                }

                                $valueDash['result'] = $allData;
                            }
                        }
                        $data[] = $valueDash;
                    }
                } catch (Exception $ex) {
                    return response()->json(
                        ['result' => 'error', 'data' => $ex->getMessage() . ' ' . $ex->getLine() . ' ' . $ex->getFile()],
                        201
                    );
                }
            }

            return response()->json(['result' => 'ok', 'idPannel' => $idPannel, 'content' => $data], 201);
        }
    }

    public function store(Request $request)
    {
        try {
            $userName = Session::get('user')->name;
            $userEmail = Session::get('user')->email;
            $userPhone = Session::get('user')->phone;
            $userId = Session::get('user')->_id;
            $dashboard = new DashboardCRM();
            foreach ($request->data as $key => $value) {
                $dashboard->{$key} = !empty($value) ? $value : '';
            }
            if ($request->idPannel == '') {
                $dashboard->ownerName = $userName;
                $dashboard->ownerEmail = $userEmail;
                $dashboard->ownerPhone = $userPhone;
                $dashboard->ownerId = $userId;
                $dashboard->save();
            } else {
                DashboardCRM::where('_id', $request->idPannel)->update($dashboard->toArray());
            }
            $dataUser = Auth::user();
            (new GeneralFunctionsModuleCRMController)->storeLog($dataUser, 'add', 'dashboard', 'Create a record');
            return response()->json(['result' => 'ok'], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'module' => $ex->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try 
        {
            $userEmail = Session::get('user')->email;

            $getDashboard = DashboardCRM::where('_id', $request->idDashboard)->get();
            $newData = [];
            if ($getDashboard) {
                foreach ($getDashboard[0]['graphics'] as $key => $value) {
                    if ($value['i'] == $request->positionPanelSelected) {
                        $value['data'] = $request->formValues;
                    }
                    array_push($newData, $value);
                }

                $data = [];
                $data["graphics"] = $newData;
                $data['wolkvox_usuario_modificacion'] = $userEmail;
                DashboardCRM::where('_id', $request->idDashboard)->update($data);
            }

            return response()->json(['result' => 'ok'], 201);
        } catch (Exception $ex) {
            return response()->json(['result' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    public function lineListData($module, $action, $filterBy, $from, $to, $measures, $sources, $argument)
    {

        $moduleDefault = array(
            'companies', 'contacts', 'faqs', 'products', 'quotes',
            'leads', 'opportunities', 'forecasts', 'collections',
            'projects', 'cases'
        );
    
        if (in_array($module, $moduleDefault)) {
            $module = 'wolkvox_' . $module;
        }
    
        $groupData = [];
        $projData = [];
        $lines = [
            [
                '$group' =>
                [
                    '_id' =>
                    [
                        'value' =>
                        ['$cond' => [['$eq' => ['$' . $measures, '']], 'None', '$' . $measures]],
                        'name' =>
                        ['$cond' => [['$eq' => ['$' . $measures, '']], 'None', '$' . $measures]]
                    ]
                ]
            ]
        ];
        $resul = DB::collection($module)->raw(function ($collection) use ($lines) {
            return $collection->aggregate($lines);
        })->toArray();
        $sources = array();
        $sources = array_map(function ($array_item) {
            return $array_item["_id"];
        }, $resul);
        $lines2 = [
            [
                '$group' =>
                [
                    '_id' =>
                    ['$cond' => [['$eq' => ['$' . $measures, '']], 'None', '$' . $measures]]
                ]
            ]
        ];
        $resul2 = DB::collection($module)->raw(function ($collection) use ($lines2) {
            return $collection->aggregate($lines2);
        })->toArray();
        $registrosGraficar = array();
        $registrosGraficar = array_map(function ($array_item) {
            return $array_item["_id"];
        }, $resul2);
    
        if (isset($filterBy) && !empty($filterBy)) {
            $dateFilterPipeline = ['$match' => [
                $filterBy => [
                    '$gte' => new \MongoDB\BSON\UTCDateTime(strtotime($from) * 1000),
                    '$lte' => new \MongoDB\BSON\UTCDateTime(strtotime($to) * 1000),
                ]
            ]];
        } else {
            $dateFilterPipeline = [];
        }
    
        $groupData['_id'] = '$' . $argument;
        $projData = [
            '_id' => 0,
            $argument => '$_id',
        ];
    
        foreach ($registrosGraficar as $key => $resultdata) {
            $groupData[$resultdata] = ['$sum' => ['$cond' => [['$eq' => ['$' . $measures, $resultdata]], 1, 0]]];
            $projData[$resultdata] = 1;
        }
    
        $group = ['$group' => $groupData];
        $project = ['$project' => $projData];
    
        $pipeline = [
            $group,
            $project
        ];
    
        array_unshift($pipeline, $dateFilterPipeline);
    
        $data = DB::collection($module)->raw(function ($collection) use ($pipeline) {
            return $collection->aggregate($pipeline);
        })->toArray();
    
        return ['content' => $data, 'argument' => $argument, 'sources' => $sources,'module' => $module];
    }

    public function basiclListData($module, $action, $filterBy, $from, $to, $measures, $sources, $argument)
    {

        $lines = [];
        if (isset($filterBy) && !empty($filterBy)) {
            $match = ['$match' => [
                $filterBy => [
                    '$gte' => new \MongoDB\BSON\UTCDateTime(strtotime($from) * 1000),
                    '$lte' => new \MongoDB\BSON\UTCDateTime(strtotime($to) * 1000),
                ]
            ]];
            array_push($lines, $match);
        }

        if ($action == 'group') {
            $group = ['$group' => [
                '_id' => '$' . $argument,
                'value' => ['$sum' => 1]
            ]];
        } else {
            $group = ['$group' => [
                '_id' => '$' . $argument,
                'value' => ['$sum' => '$' . $sources]
            ]];
        }

        array_push($lines, $group);

        $project =  [
            '$project' => [
                '_id' => 0,
                'argument' => '$_id',
                'value' => '$value'
            ]
        ];

        array_push($lines, $project);

        $result = DB::collection('wolkvox_' . $module)->raw(function ($collection) use ($lines) {
            return $collection->aggregate($lines);
        })->toArray();

        return $result;
    }
}
