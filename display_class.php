<?php
require_once "vendor/autoload.php";
$dbHost='localhost';
$dbPort='27017';
$client=new MongoDB\Client;
$connection=new MongoDB\Driver\Manager("mongodb://$dbHost:$dbPort");

$class = $_GET['text'];
$sector = $_GET['option'];

if ($class == 'ALL'){
    $filter = ['stockSector' => $sector];
}
else{
    $filter = array(
        '$and' => array( 
           array('class' => $class), 
           array('stockSector' => $sector)
        )
     );
}


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