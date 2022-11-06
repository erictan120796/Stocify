<?php
require_once "vendor/autoload.php";
$dbHost='localhost';
$dbPort='27017';
$client=new MongoDB\Client;
$connection=new MongoDB\Driver\Manager("mongodb://$dbHost:$dbPort");

$sector = $_GET['text'];
$filter = ['stockSector' => $sector];
$query=new MongoDB\Driver\Query($filter);
$id = array();

$stocklist=$connection->executeQuery('Statis_ByStock.Stock_list', $query);
foreach($stocklist as $row) {
    if ($row->stockSector == 'Transportation&Logistics'){
        $row->stockSector = 'Logistics';
    }
    $id[]=$row;
    
}

echo json_encode($id);
?>