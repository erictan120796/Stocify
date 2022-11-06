<?php
require_once "vendor/autoload.php";
$dbHost='localhost';
$dbPort='27017';
$client=new MongoDB\Client;
$connection=new MongoDB\Driver\Manager("mongodb://$dbHost:$dbPort");
$filter = [];
$query=new MongoDB\Driver\Query($filter);
$id = array();

shell_exec("python crawl_latest_price.py");
shell_exec("python scrape_latest_price.py");
shell_exec("python stock_price_merge.py");
shell_exec("python statistical_classifier.py");

$stocklist=$connection->executeQuery('Statis_ByStock.Stock_list', $query);
foreach($stocklist as $row) {
    if ($row->stockSector == 'Transportation&Logistics'){
        $row->stockSector = 'Logistics';
    }
    $id[]=$row;
    
}

echo json_encode($id);
?>

