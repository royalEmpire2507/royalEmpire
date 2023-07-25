<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

require_once(__DIR__.'/../perf/mongoTraits/mongodbClass.php');
require_once(__DIR__.'/../perf/DatastoreClassFunctions.php');
require_once __DIR__ . '/../vendor/autoload.php';
session_start();
// Pass Modules
$dataStore = new DatastoreClassFunctions(true, 'America/Bogota', 'micro', 'REST' );
$mongodb = new mongodbClass("America/Bogota", 'micro');
$collection = $mongodb->MongoDB->wolkvox_modules;
$query = $dataStore->DsClient->query()
  ->kind('wolkvox_cliente_admin_module');

$result = $dataStore->DsClient->runQuery($query);
echo "<pre>";
// foreach ($result as $key => $entity){
//   echo "-----------------------------------------------------------------------------------------</br>";
//   $insertToMongo = transformDatastoreToMongoDB($entity);
//   $save = $collection->insertOne($insertToMongo);
//   $insertToMongo['id'] = $save->getInsertedId();
//   echo "-----------------------------------------------------------------------------------------</br>";
// }

// $queryFields = $dataStore->DsClient->query()
// ->kind('wolkvox_cliente_admin_field');
$key = $dataStore->DsClient->key('wolkvox_cliente_admin', 'ppal')
			->pathElement('wolkvox_cliente_admin', 'modules')
			->pathElement('wolkvox_cliente_admin_module', 'leads');

$queryFields = $dataStore->DsClient->query()->kind('wolkvox_cliente_admin_field');
$queryFields->hasAncestor($key);

$resultFields = $dataStore->DsClient->runQuery($queryFields);
foreach ($resultFields as $key => $entity){
  echo "-----------------------------------------------------------------------------------------</br>";
  var_dump($entity);
  echo "-----------------------------------------------------------------------------------------</br>";
}

function transformDatastoreToMongoDB($datastoreRecord)
{
  
  $icons = ['Plus','Minus','CirclePlus','Search','Female','Male','Aim','House','FullScreen','Loading','Link','Service','Pointer','Star','Notification','Connection','ChatDotRound','Setting','Clock','Position','Discount','Odometer','ChatSquare','ChatRound','ChatLineRound','ChatLineSquare','ChatDotSquare','View','Hide','Unlock','Lock','RefreshRight','RefreshLeft','Refresh','Bell','MuteNotification','User','Check','CircleCheck','Warning','CircleClose','Close','PieChart','More','Compass','Filter','Switch','Select','SemiSelect','CloseBold','EditPen','Edit','Message','MessageBox','TurnOff','Finished','Delete','Crop','SwitchButton','Operation','Open','Remove','ZoomOut','ZoomIn','InfoFilled','CircleCheckFilled','SuccessFilled','WarningFilled','CircleCloseFilled','QuestionFilled','WarnTriangleFilled','UserFilled','MoreFilled','Tools','HomeFilled','Menu','UploadFilled','Avatar','HelpFilled','Share','StarFilled','Comment','Histogram','Grid','Promotion','DeleteFilled','RemoveFilled','CirclePlusFilled','ArrowLeft','ArrowUp','ArrowRight','ArrowDown','ArrowLeftBold','ArrowUpBold','ArrowRightBold','ArrowDownBold','DArrowRight','DArrowLeft','Download','Upload','Top','Bottom','Back','Right','TopRight','TopLeft','BottomRight','BottomLeft','Sort','SortUp','SortDown','Rank','CaretLeft','CaretTop','CaretRight','CaretBottom','DCaret','Expand','Fold','DocumentAdd','Document','Notebook','Tickets','Memo','Collection','Postcard','ScaleToOriginal','SetUp','DocumentDelete','DocumentChecked','DataBoard','DataAnalysis','CopyDocument','FolderChecked','Files','Folder','FolderDelete','FolderRemove','FolderOpened','DocumentCopy','DocumentRemove','FolderAdd','FirstAidKit','Reading','DataLine','Management','Checked','Ticket','Failed','TrendCharts','List','Microphone','Mute','Mic','VideoPause','VideoCamera','VideoPlay','Headset','Monitor','Film','Camera','Picture','PictureRounded','Iphone','Cellphone','VideoCameraFilled','PictureFilled','Platform','CameraFilled','BellFilled','Location','LocationInformation','DeleteLocation','Coordinate','Bicycle','OfficeBuilding','School','Guide','AddLocation','MapLocation','Place','LocationFilled','Van','Watermelon','Pear','NoSmoking','Smoking','Mug','GobletSquareFull','GobletFull','KnifeFork','Sugar','Bowl','MilkTea','Lollipop','Coffee','Chicken','Dish','IceTea','ColdDrink','CoffeeCup','DishDot','IceDrink','IceCream','Dessert','IceCreamSquare','ForkSpoon','IceCreamRound','Food','HotWater','Grape','Fries','Apple','Burger','Goblet','GobletSquare','Orange','Cherry','Printer','Calendar','CreditCard','Box','Money','Refrigerator','Cpu','Football','Brush','Suitcase','SuitcaseLine','Umbrella','AlarmClock','Medal','GoldMedal','Present','Mouse','Watch','QuartzWatch','Magnet','Help','Soccer','ToiletPaper','ReadingLamp','Paperclip','MagicStick','Basketball','Baseball','Coin','Goods','Sell','SoldOut','Key','ShoppingCart','ShoppingCartFull','ShoppingTrolley','Phone','Scissor','Handbag','ShoppingBag','Trophy','TrophyBase','Stopwatch','Timer','CollectionTag','TakeawayBox','PriceTag','Wallet','Opportunity','PhoneFilled','WalletFilled','GoodsFilled','Flag','BrushFilled','Briefcase','Stamp','Sunrise','Sunny','Ship','MostlyCloudy','PartlyCloudy','Sunset','Drizzling','Pouring','Cloudy','Moon','MoonNight','Lightning','ChromeFilled','Eleme','ElemeFilled','ElementPlus','Shop','SwitchFilled','WindPower'];
  // Obtener una clave aleatoria del array
  $randomKey = array_rand($icons);

  // Obtener el valor correspondiente a la clave aleatoria
  $randomValue = $icons[$randomKey];

    // Generar el documento de MongoDB
    $mongoDBDocument = array(
        "name" => $datastoreRecord["name"],
        "icon" => $randomValue,
        "type" => $datastoreRecord["type"],
        "namePlural" => $datastoreRecord["namePlural"],
        "nameSingular" => $datastoreRecord["nameSingular"],
        "viewFields" => $datastoreRecord["viewFields"],
        "viewFieldsTypes" => $datastoreRecord["viewFieldsTypes"],
        "updated_at" => convertDatastoreDateToMongoDate($datastoreRecord["wolkvox_fecha_modificacion"]),
        "created_at" => convertDatastoreDateToMongoDate($datastoreRecord["wolkvox_fecha_creacion"]),
        "socialConfig" => array()
    );    
    // Retornar el documento de MongoDB
    return $mongoDBDocument;
}

function convertDatastoreDateToMongoDate($date) {
  if(!is_null($date)){
    $mongoDate = new MongoDB\BSON\UTCDateTime(strtotime($date->format('Y-m-d H:i:s.u')) * 1000);
  }else{
    $fecha_actual = date("Y-m-d H:i:s");
    $mongoDate = new MongoDB\BSON\UTCDateTime(strtotime($fecha_actual) * 1000);
  }
  return $mongoDate;
}

//End Pass Modules
exit();

//Pass users
$dataStore = new DatastoreClassFunctions(true, 'America/Bogota', 'wolkvox_admin', 'REST' );
$mongodb = new mongodbClass("America/Bogota", 'crmvox');
$collection = $mongodb->MongoDB->users;

$query = $dataStore->DsClient->query()
  ->kind('Usuarios')
  ->filter("nit", '=', 'activa-ceba')
  ->filter("active", '=', true);
  
$result = $dataStore->DsClient->runQuery($query);

foreach ($result as $key => $entity){
  $data = [];
  $data[] = $entity->get();
  // if($data[0]['email'] == 'ADMIN_@modulotest01'){
    if($data[0]['rol'] == 'administrador'){
    // var_dump($key);
    $toI = array();
    $toI["name"] = $data[0]["nombres"];
    $toI["email"] = $data[0]["email"];
    $toI["city"] = $data[0]["ciudad"];
    $toI["direction"] = $data[0]["direccion"];
    $toI["doc"] = $data[0]["input_cc"];
    $toI["levelUser"] = $data[0]["levelUser"];
    $toI["license"] = $data[0]["active"];
    $toI["phone"] = $data[0]["telefono"];
    $toI["rol"] = '61f941ee74edf91b56582bc2';
    $toI["password"] = $data[0]["password"];
    try {
      $datetime = new DateTime();
      $timezone = new DateTimeZone('EST');
      $datetime->setTimezone($timezone);
      $toI["created_at"] = new MongoDB\BSON\UTCDateTime($datetime);
    } catch (\Throwable $th) {
      
    }
    $toI["profile"] = $data[0]["rolUser"];
    $toI["userEmail"] = $data[0]["userEmail"];
    $save = $collection->insertOne($toI);
    echo "<pre>";
    var_dump($save->getInsertedId());
  }
}
exit();
//End Pass users

use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\Query\Query;

$dataStore = new DatastoreClassFunctions( true , 'America/Bogota', 'micro', 'REST' );

$data = $dataStore->listSocial(
  [
    "correo electronico" => "allaneximo@eximo.com",
    "Telefono principal" => ["87456060", "country" => "Costa Rica", "code" => "506"],
    "idcontact" => "12345678", 
    "channel" => "whatsapp"
  ],
  'eximo-dospinos-asociados'
  );

var_dump($data);

exit();