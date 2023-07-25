
<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ (E_DEPRECATED));

abstract class Constans
{
	const USAR_DATASTORE = TRUE;
	const NO_USAR_DATASTORE = FALSE;
}

require_once __DIR__ . '/../server/vendor/autoload.php';
require_once __DIR__ . '/../server/perf/mongoTraits/mongodbClass.php';
require_once __DIR__ . '/../server/perf/DatastoreClassFunctions.php';

$dataStore = new DatastoreClassFunctions(Constans::USAR_DATASTORE, 'EST', 'modulotest01');

function sendEmail($from, $to, $subject, $text){
	try {
		$mailgun_url = 'https://api.mailgun.net/v3/email.wolkvox.com/messages';
		$mailgun_key = 'api:key-7eef6ddda53a6b0c7f930a149d2ca311';

		$array_data = array(
			'from'=> $from,
			'to'=> $to,
			'subject'=> $subject,
			'text'=> $text
		);

		$session = curl_init($mailgun_url);
		curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($session, CURLOPT_USERPWD, $mailgun_key);
		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_POSTFIELDS, $array_data);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($session);

		curl_close($session);
		$results = $response;

		return ['result' => 'ok','message'=> json_decode($results)];
	} catch (Exception $ex) {
		return ['result' => 'error','message'=>$ex->getMessage()];
	}
}

function returnSendEmail($case, $notify){
	$email = '';
	if ($notify['owner'] === true) {
		
		if ($notify['owner'] === true) {
			if (isset($case['emailOwner']))
				$email .= $case['emailOwner'] . ',';
		}
	}

	if ($notify['responsible'] && $case['idOwner'] != $case['idResponsible']) {

		if ($notify['responsible'] === true) {
			if (isset($case['emailResponsible']))
				$email .= $case['emailResponsible'] . ',';
		}
	}

	if ($notify['contact'] === true) {

		if ($notify['contact'] === true) {								
			if (isset($case['emailcontact']) && !empty($case['emailcontact']))
				$email .= $case['emailcontact'] . ',';
		}
	}

	return $email;
}

try {
	$mongodb = new mongodbClass();
	$bds = $mongodb->listBD();


	foreach ($bds as $key => $actualBd) {

		
		if ($actualBd == "crmvox") {
			echo "<pre>";
			if (!empty($mongodb->listCollections($actualBd, ['filter' => ['name' => 'wolkvox_cases']]))){
				$mongodbActual = new mongodbClass('EST', $actualBd);
				$collectionCases = $mongodbActual->MongoDB->wolkvox_cases;
				$collectionModules = $mongodbActual->MongoDB->wolkvox_modules;
				$collectionTemplate = $mongodbActual->MongoDB->wolkvox_template;

				$modules = $collectionModules->findOne(['name' => 'cases']);
				if(isset($modules)){
					if(isset($modules->configDetails)){
						$arguments = [
							[
								'$project' => [
									"porcentaje_vencimiento" => [
										'$round' => [
											['$divide' => [
												['$multiply' => [['$subtract' => ['$$NOW', '$created_at']], 100]],
												['$subtract' => ['$timeEstimated', '$created_at']]
											]], 2
										]
									],
									'Status' => 1,
									'StatusCondition' => 1,
									'caseExpired' => 1,
									'caseExpiring' => 1,
									'Type' => 1,
									'Prefix' => 1,
									'idcontact' => 1,
									'emailcontact' => 1,
									'Owner' => 1,
									'idOwner' => 1,
									'emailOwner' => 1,
									'Responsible' => 1,
									'idResponsible' => 1,
									'emailResponsible' => 1
			
								],
							],
							[
								'$match' => [
									'StatusCondition' => ['$ne' => 'closed'],
									'caseExpired' => ['$ne' => true],
									'porcentaje_vencimiento' => ['$gte' => 74],
								]
							]
						];
						
						foreach ($modules->configDetails as $keyCases => $resultTypes ) {
							$notifies = (array)$resultTypes->notify;
	
							$allNotify[$keyCases] = array_filter($notifies, function ($v, $k) {
								return $k === 'Expired' || $k === 'Expiring';
							}, ARRAY_FILTER_USE_BOTH);
							
						}
	
						$casosVenciendose = $collectionCases->aggregate($arguments);
	
						foreach ($casosVenciendose as $key => $casoActual) {
							var_dump($casoActual);
							if(isset($allNotify[$casoActual['Type']])){
	
								// esta a punto de vencerse
								if ($casoActual['porcentaje_vencimiento'] > 70 && $casoActual['porcentaje_vencimiento'] < 99 && !isset($casoActual['caseExpiring'])) {
	
									// cambio el estado
									$collectionCases->updateOne(["_id" => $casoActual['_id']], ['$set' => ['caseExpiring' => true]]);
									
									if (isset($allNotify[$casoActual['Type']]['Expiring'])) {
										
										$send_email = '';
										$idReg = (string)$casoActual['_id'];
										$event = (array)$allNotify[$casoActual['Type']]['Expiring'];
	
										$send_email = returnSendEmail($casoActual, $event);
										
										if ($send_email !== '' && $send_email !== ',') {
											if (isset($event['template'])) {
												$template = $collectionTemplate->findOne(['_id' => new MongoDB\BSON\ObjectID($event['template'])]);
												sendEmail($template['oriEmail'], $send_email, $template['subject'], $template['content']);
												continue;
	
											}
										}
									}
									
								}else if ($casoActual['porcentaje_vencimiento'] > 99) {
																	
									// cambio el estado 
									$collectionCases->updateOne(["_id" => $casoActual['_id']], [
										'$set' => ['caseExpired' => true],
										'$unset' => ['caseExpiring' => true],
										'$push' => ['history' => ['date' => date('Y/m/d H:i:s'), 'message' => 'Case expired', 'user' => 'Wolkvox CRM']]
									]);
									
									if (isset($allNotify[$casoActual['Type']]['Expired'])) {
										
										$send_email = '';
										$idReg = (string)$casoActual['_id'];
										$event = (array)$allNotify[$casoActual['Type']]['Expired'];
	
										$send_email = returnSendEmail($casoActual, $event);
										
										if ($send_email !== '' && $send_email !== ',') {
											if (isset($event['template'])) {
												$template = $collectionTemplate->findOne(['_id' => new MongoDB\BSON\ObjectID($event['template'])]);
												sendEmail($template['oriEmail'], $send_email, $template['subject'], $template['content']);
												continue;
											}
										}
									}
								}
	
							}
						}
					}
				}
			};
			
		}else{
			if (!empty($casesCollection = $mongodb->listCollections($actualBd, ['filter' => ['name' => 'cases']]))) {

				$mongodbActual = new mongodbClass('EST', $actualBd);
				$collection = $mongodbActual->MongoDB->cases;
				$arguments = [
					[
						'$project' => [
							"porcentaje_vencimiento" => [
								'$round' => [
									['$divide' => [
										['$multiply' => [['$subtract' => ['$$NOW', '$wolkvox_fecha_creacion']], 100]],
										['$subtract' => ['$timeEstimated', '$wolkvox_fecha_creacion']]
									]], 2
								]
							],
							'status' => 1,
							'caseExpired' => 1,
							'caseExpiring' => 1,
							'caseType' => 1,
							'idPrefijo' => 1,
							'contact' => 1,
							'contactEmail' => 1,
							'owner' => 1,
							'responsible' => 1

						],
					],
					[
						'$match' => [
							'status.value' => ['$ne' => 'closed'],
							'caseExpired' => ['$ne' => true],
							'porcentaje_vencimiento' => [
								'$gte' => 74
							],
						]
					]
				];
				$allNotify = [];

				$dataStore = new DatastoreClassFunctions(Constans::USAR_DATASTORE, 'EST', $actualBd);

				$RequestNotify = json_decode($dataStore->listNotify('Request'));
				$ComplaintsNotify = json_decode($dataStore->listNotify('Complaints'));
				$ClaimsNotify = json_decode($dataStore->listNotify('Claims'));
				$SuggestionsNotify = json_decode($dataStore->listNotify('Suggestions'));
				$IncidentesNotify = json_decode($dataStore->listNotify('Incidentes'));

				$allNotify['Request'] = array_filter($RequestNotify, function ($v, $k) {
					return $v->event === 'Expired' || $v->event === 'Expiring';
				}, ARRAY_FILTER_USE_BOTH);

				$allNotify['Complaints'] = array_filter($ComplaintsNotify, function ($v, $k) {
					return $v->event === 'Expired' || $v->event === 'Expiring';
				}, ARRAY_FILTER_USE_BOTH);

				$allNotify['Claims'] = array_filter($ClaimsNotify, function ($v, $k) {
					return $v->event === 'Expired' || $v->event === 'Expiring';
				}, ARRAY_FILTER_USE_BOTH);

				$allNotify['Suggestions'] = array_filter($SuggestionsNotify, function ($v, $k) {
					return $v->event === 'Expired' || $v->event === 'Expiring';
				}, ARRAY_FILTER_USE_BOTH);

				$allNotify['Incidentes'] = array_filter($IncidentesNotify, function ($v, $k) {
					return $v->event === 'Expired' || $v->event === 'Expiring';
				}, ARRAY_FILTER_USE_BOTH);







				$casosVenciendose = $mongodbActual->MongoDB->cases->aggregate($arguments);
				$mails = true;
				$subject = 'Wolkvox CRM: ';

				foreach ($casosVenciendose as $key => $casoActual) {


					// esta a punto de vencerse
					if (
						$casoActual['porcentaje_vencimiento'] > 70 && $casoActual['porcentaje_vencimiento'] < 99
						&& !isset($casoActual['caseExpiring'])
					) {


						// cambio el estado
						$collection->updateOne(["_id" => $casoActual['_id']], ['$set' => ['caseExpiring' => true]]);

						$send_email = '';

						// notifico
						if (in_array('Expiring', array_column($allNotify[$casoActual['caseType']], 'event'))) {


							$event = array_filter($allNotify[$casoActual['caseType']], function ($noty) {
								return $noty->event == 'Expiring';
							});

							$event = (array) array_values($event)[0];




							$idReg = (string)$casoActual['_id'];



							if (array_column($allNotify[$casoActual['caseType']], 'owner')) {

								if (array_column($allNotify[$casoActual['caseType']], 'owner')[0] === true) {
								if (isset($casoActual['owner']['mail']))
									$send_email .= $casoActual['owner']['mail'] . ',';
								}
							}

							if (array_column($allNotify[$casoActual['caseType']], 'responsible') && $casoActual['owner']['id'] != $casoActual['responsible']['id']) {

								if (array_column($allNotify[$casoActual['caseType']], 'responsible')[0] === true) {
								if (isset($casoActual['responsible']['mail']))
									$send_email .= $casoActual['responsible']['mail'] . ',';
								}
							}
							if (array_column($allNotify[$casoActual['caseType']], 'contact')) {

								// expiring								
								if (array_column($allNotify[$casoActual['caseType']], 'contact')[0] === true) {
								if (isset($casoActual['contactEmail']))
									$send_email .= $casoActual['contactEmail'] . ',';
								}
							}

							if ($send_email !== '') {
								if (isset($event['template'])) {
									$a = json_decode($mongodbActual->sendEmailTemplate($idReg, $event['template'], $send_email, 'cases', 'cases'));
									if (isset($a->message)) {
										if (strpos($a->message, 'Queued') >= 0)
											return 'properly executed action';
										else
											return 'action not executed correctly';
									} else {
										return 'action not executed correctly';
									}
									continue;
								} else {

									$dataStore->sendMail(substr($send_email, 0, -1), 'crm@wolkvox.com', $subject . 'Venciéndose ' . $casoActual['idPrefijo'], 'El caso ' . $casoActual['idPrefijo'] . ' está a punto de vencerse.', $mails, $actualBd);
									continue;
								}
							}
						}
					}
					// si ya se vencio
					else if ($casoActual['porcentaje_vencimiento'] > 99) {

						// cambio el estado 
						$collection->updateOne(["_id" => $casoActual['_id']], [
							'$set' => ['caseExpired' => true],
							'$push' => ['history' => ['date' => date('Y/m/d H:i:s'), 'message' => 'Case expired', 'user' => 'Wolkvox CRM']],
							'$unset' => ['caseExpiring' => true]
						]);


						// notifico
						if (in_array('Expired', array_column($allNotify[$casoActual['caseType']], 'event'))) {


							$event = array_filter($allNotify[$casoActual['caseType']], function ($noty) {
								return $noty->event == 'Expired';
							});

							$event = (array) array_values($event)[0];



							$idReg = (string)$casoActual['_id'];



							$send_email = '';

							if (array_column($allNotify[$casoActual['caseType']], 'owner')) {

								if (array_column($allNotify[$casoActual['caseType']], 'owner')[0] === true) {
								if (isset($casoActual['owner']['mail']))
									$send_email .= $casoActual['owner']['mail'] . ',';
								}
							}

							if (array_column($allNotify[$casoActual['caseType']], 'responsible') && $casoActual['owner']['id'] != $casoActual['responsible']['id']) {

								if (array_column($allNotify[$casoActual['caseType']], 'responsible')[0] === true) {
								if (isset($casoActual['responsible']['mail']))
									$send_email .= $casoActual['responsible']['mail'] . ',';
								}
							}
							if (array_column($allNotify[$casoActual['caseType']], 'contact')) {

								// Expired
								if (array_column($allNotify[$casoActual['caseType']], 'contact')[0] === true) {								
								if (isset($casoActual['contactEmail']))
									$send_email .= $casoActual['contactEmail'] . ',';
								}
							}

							if ($send_email !== '') {
								if (isset($event['template'])) {
									$a = json_decode($mongodbActual->sendEmailTemplate($idReg, $event['template'], $send_email, 'cases', 'cases'));

									if (isset($a->message)) {
										if (strpos($a->message, 'Queued') >= 0)
											return 'properly executed action';
										else
											return 'action not executed correctly';
									} else {
										return 'action not executed correctly';
									}
									continue;
								} else {

									$dataStore->sendMail(substr($send_email, 0, -1), 'crm@wolkvox.com', $subject . 'Venciéndose ' . $casoActual['idPrefijo'], 'El caso ' . $casoActual['idPrefijo'] . ' está a punto de vencerse.', $mails, $actualBd);
									continue;
								}
							}
						}
					}
				}
			}
		}
	}
} catch (Exception $e) {
	// $dataStore->sendMail(
	// 	'andres.guzman@wolkvox.com',
	// 	'crm@wolkvox.com',
	// 	'CRON FALLA',
	// 	'FALLÓ EL CRON EN EL TRYCATCH: '. $bdSelected . ' --- ' . (string)$e,
	// 	true,
	// 	'modulotest01'
	// );
	var_dump($e);
}