<?php
require_once "vendor/autoload.php";
$dbHost='localhost';
$dbPort='27017';
$client=new MongoDB\Client;
$connection=new MongoDB\Driver\Manager("mongodb://$dbHost:$dbPort");
$query=new MongoDB\Driver\Query([]);

$stockName = $_GET['text'];

$predictModelFound = False;
$stockDetail =$connection->executeQuery('Stock_Details.stock_id_list', $query);
foreach($stockDetail as $row){
  if ($row->stockName == $stockName ){
    if ($row->class_model == 'Yes'){
      $predictModelFound = True;
    }
    break;
  }
}

if ($predictModelFound == False){
  echo "No Model Found";
}
else{

  shell_exec("python ml_classifier.py $stockName ");

  $stocklist=$connection->executeQuery('Ml_ByStock.Stock_list', $query);
  $id = array();
  foreach($stocklist as $row){ 
    if ($row->stockName == $stockName){
      $classs = $row->Class;
      $accuracy = $row->Accuracy;
      $algo = $row->Algo;
      $para = $row->Parameter;
      break;
    }
  }
  
  echo "$classs+$accuracy+$algo+$para";
}


?>