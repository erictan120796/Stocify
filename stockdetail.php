<?php 
require_once "vendor/autoload.php";
$dbHost='localhost';
$dbPort='27017';
$client=new MongoDB\Client;
$connection=new MongoDB\Driver\Manager("mongodb://$dbHost:$dbPort");
$query=new MongoDB\Driver\Query([]);
$stocklist=$connection->executeQuery('Statis_ByStock.Stock_list', $query);
$stockDetail =$connection->executeQuery('Stock_Details.stock_id_list', $query);
$stockName = $_COOKIE['gfg'];
$id = array();
foreach($stockDetail as $row){
  $id[] = $row;
  if ($row->stockName == $stockName){
    $collection_name = $row->stockId;
  }
}

$db = (new MongoDB\Client)->Stock_Historical_Data;
$collection = $db->selectCollection($collection_name);
$cursor = $collection->find();

$idd = array();
foreach($cursor as $row){
  $idd[] = $row;
}

$db = (new MongoDB\Client)->Stock_Quarter;
$collection = $db->selectCollection($stockName);
$stockQuarter = $collection->find();

$quarterdetails = array();
foreach($stockQuarter as $row){
  $quarterdetails[] = $row;
}

$selected_stock = array();

foreach($stocklist as $row) {
  if($row->stockName == $stockName ){
    $selected_stock[]=$row;
    $sector = $row->stockSector;
    break;
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title> <?php echo $stockName; ?></title>

  <link href="dist/css/stockdetail.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="http://localhost/Stocify/stock.php" class="nav-link">Home</a>
      </li>
    </ul>

    

    <!-- Right navbar links -->
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="http://localhost/Stocify/stock.php" class="brand-link">
      <img src="dist/img/StocifyLogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Stocify</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/profile.jpeg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a class="d-block">Welcome To Stocify !!!</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/Stocify/stock.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.html" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo $stockName; ?></p>
                </a>
              </li>
            </ul>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 id="stockName" class="m-0 text-dark"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="http://localhost/Stocify/stock.php">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $sector;?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
              
      <div class="row">
          <div class="col-12 col-sm-6 col-md-3" data-toggle="modal" data-target="#modal-lg-classs">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                <div class="info-box-content">
                  <span id = "price"></span>
                  <br>
                  <span id="ss_class"></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3" data-toggle="modal" data-target="#modal-lg-eps">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-number">P/EPS (%)</span>
                <span id="p_eps"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3" data-toggle="modal" data-target="#modal-lg-rps">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-number">P/RPS</span>
                <span id="p_rps"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3" data-toggle="modal" data-target="#modal-lg-naps">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-number">P/NAPS</span>
                <span id = "p_naps"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="modal fade" id="modal-lg-classs">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Overview</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style='height:90%;'>
              
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <h5>Result By Statistical Modal</h5>
                  <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                    <div class="info-box-content">
                      <span id = "price1"></span>
                      <br>
                      <span id="ss_class1"></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h5 id='ModalTitle' style='display:none;'>Result By AI Modal</h5>
                    <button id='predictButton' type="button" onclick='goPython()' class="btn btn-block btn-info btn-lg">Try Our AI Prediction</button>
                    <div id='resultModal' style='display:none'>
                      <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-number">Predicted Class</span>
                          <span id="modal_class_result"></span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>

                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="far fa-bookmark"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Accuracy</span>
                          <span class="info-box-number" id='accuracy'></span>
                          <span class="info-box-text">Model</span>
                          <span class="info-box-number" id='algorithm'></span>
                          <span class="info-box-text">Parameter</span>
                          <span class="info-box-number" id='parameter'></span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                    </div>  
                    <div id ='loading' style='display:none'>
                        <div class="spinner-grow text-primary" role="status" >
                          <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-secondary" role="status" >
                          <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-success" role="status" >
                          <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-danger" role="status" >
                          <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-warning" role="status" >
                          <span class="sr-only">Loading...</span>
                        </div>
                        <div class="spinner-grow text-info" role="status" >
                          <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                </div>
              </div>

              
              

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->



        <div class="modal fade" id="modal-lg-eps">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Overview of Statistical Modal for <b>P/EPS</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style='height:90%;'>
              <div id='chart_P_EPS' style ='text-align: center;'></div>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <div class="modal fade" id="modal-lg-rps">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Overview of Statistical Modal for <b>P/RPS</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style='height:90%;'>
              <div id='chart_P_RPS' style ='text-align: center;'></div>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <div class="modal fade" id="modal-lg-naps">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Overview of Statistical Modal for <b>P/NAPS</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style='height:90%;'>
              <div id='chart_P_NAPS' style ='text-align: center;'></div>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->



        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Quarter Reports</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-hover table-head-fixed text-nowrap">
                  <thead>
                    <tr>
                      <th>Quarter</th>
                      <!-- <th>QoQ</th> -->
                      <th>EPS</th>
                      <th>RPS</th>
                      <th>NAPS</th>
                      <th>Ann P/EPS</th>
                      <th>E. P/EPS</th>
                      <th>Ann P/RPS</th>
                      <th>E. P/RPS</th>
                      <th>Ann P/NAPS</th>
                      <th>E. P/NAPS</th>
                      <th>Ann Price</th>
                      <th>E. Price</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($quarterdetails as $document) { ?>
                    <tr>
                      <td><?php echo $document->quarter?></td>
                      <td><?php echo $document->EPS?></td>
                      <td><?php echo $document->RPS?></td>
                      <td><?php echo $document->NAPS?></td>
                      <td><?php echo $document->ANN_P_EPS?></td> 
                      <td><?php echo $document->EOQ_P_EPS?></td>
                      <td><?php echo $document->ANN_P_RPS?></td>
                      <td><?php echo $document->EOQ_P_RPS?></td>
                      <td><?php echo $document->ANN_P_NAPS?></td> 
                      <td><?php echo $document->EOQ_P_NAPS?></td>
                      <td><?php echo $document->ANN_PRICE?></td>
                      <td><?php echo $document->EOQ_PRICE?></td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <!-- /.col -->
                  <div class="col-sm-4 col-6">
                    <div class="description-block border-right">
                      <span class='abbre'>EPS = Earnings Per Share</span>
                      <br>
                      <span class='abbre'>RPS = Revenue Per Share</span>
                      <br>
                      <span class='abbre'>NAPS = Net Asset Per Share</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 col-6">
                    <div class="description-block border-right">
                    <span class='abbre'>Ann = Announcement</span>
                      <br>
                      <span class='abbre'>E. = End of Quarter</span>
                      <br>
                      <br>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 col-6">
                    <div class="description-block border-right">
                      <span class='abbre'>P/EPS = Price/Earning per Share</span>
                      <br>
                      <span class='abbre'>P/RPS = Price/Revenue per Share</span>
                      <br>
                      <span class='abbre'>P/NAPS = Price/Net Asset per Share</span>
                      <br>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
      </div><!--/. container-fluid -->
              <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col-md-8">
            <!-- MAP & BOX PANE -->
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Validation Model</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table p-0" id="validateTable">
                    <thead>
                    <tr>
                      <th>Quarter</th>
                      <th>Date(YYYY-MM-DD)</th>
                      <th>Price</th>
                      <th>20FMA %</th>
                      <th>50FMA %</th>
                      <th>100FMA %</th>
                      <th>Class By Statistic</th>
                    </tr>
                    </thead>
                    <tbody>
                      <tr><td style ='padding-left: 200px;' colspan ='8'><h3 id='hist_found'>No Historical Data Existed</h3><td></tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <div class="btn btn-sm btn-secondary float-left" id='selectDate'>
                  Select a Date : <input type="date" id="theDate" min="2018-01-01" max="2018-12-31"/>
                </div>
                <button id='validateButton' type ="button" onclick= 'validate()' class="btn btn-sm btn-secondary float-right">Validate</button>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-4">
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-success">
              <span class="info-box-icon"><i class="far fa-heart"></i></span>

              <div class="info-box-content">
                <span class="info-box-number">When to Buy</span>
                <span id="to_buy" class="info-box-number"></span>
              </div>
              <!-- /.info-box-content -->
            </div>

            <div class="info-box mb-3 bg-danger">
              <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-number">When to Sell</span>
                <span id="to_sell" class="info-box-number"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->
<script>
    var receivedData = localStorage.getItem("passData")
    var historical_data = <?php echo json_encode($idd);?>;

    var first_entry = true

    if (historical_data.length == 0 ){
      hist_found = false
    }
    else{
      hist_found = true
    }

    if (hist_found == true){
      start_date = historical_data[0]['Date']
      end_date = historical_data[historical_data.length-1]['Date']
      end_date = new Date(end_date);
      end_date.setDate(end_date.getDay() - 365)
      end_date = end_date.toISOString().split('T')[0];
      document.getElementById("theDate").setAttribute("min", start_date);
      document.getElementById("theDate").setAttribute("max", end_date);

    }


    console.log(hist_found)
    var selected_stock_details = <?php echo json_encode($selected_stock);?>;
    
    var quarter_data_list = <?php echo json_encode($quarterdetails);?>;
    quarter_data = quarter_data_list[0]['quarter']
    quarter_data_month = quarter_data.split('-')[1]

    if (quarter_data_month == 3 || quarter_data_month == 6 || quarter_data_month == 9 || quarter_data_month == 12 ){
      quarter_type = 1;
    }
    else if (quarter_data_month == 1|| quarter_data_month == 4 || quarter_data_month ==7 || quarter_data_month == 10){
      quarter_type = 2;
    }
    
    
    window.onload = function(){

        if (hist_found == false){
          // document.getElementById("hist_found").style.display = 'block'

          document.getElementById("validateButton").style.display = 'none'
          document.getElementById("selectDate").style.display = 'none'
        }
        else{
          document.getElementById("hist_found").innerHTML = 'Try our Validation Model'
        }

        document.getElementById("stockName").innerHTML = receivedData;   
        
        document.getElementById("ss_class").innerHTML = selected_stock_details[0]['overall_class'];
        document.getElementById("ss_class").className = selected_stock_details[0]['class'];

        document.getElementById("ss_class1").innerHTML = selected_stock_details[0]['overall_class'];
        document.getElementById("ss_class1").className = selected_stock_details[0]['class'];

        document.getElementById("p_eps").innerHTML = selected_stock_details[0]['P_EPS'];
        document.getElementById("p_eps").className = selected_stock_details[0]['p_eps_class'];
        
        document.getElementById("p_rps").innerHTML = selected_stock_details[0]['P_RPS'];
        document.getElementById("p_rps").className = selected_stock_details[0]['p_rps_class'];
        
        document.getElementById("p_naps").innerHTML = selected_stock_details[0]['P_NAPS'];
        document.getElementById("p_naps").className = selected_stock_details[0]['p_naps_class'];

        document.getElementById("price").innerHTML = 'RM ' + selected_stock_details[0]['Price'];
        document.getElementById("price").className = selected_stock_details[0]['class'];

        document.getElementById("price1").innerHTML = 'RM ' + selected_stock_details[0]['Price'];
        document.getElementById("price1").className = selected_stock_details[0]['class'];
        
        createCharts()
        price_to_sell_buy()

    }
    function goPython(){
      
      document.getElementById('predictButton').innerHTML = 'Training Model';
      document.getElementById('predictButton').className =  'btn btn-block btn-info disabled';
      // document.getElementById('predictButton').style.display = 'none';
      document.getElementById('loading').style.display = 'block';

            $.ajax({
              data: {text:receivedData},
              url: "ajax.php",
             context: document.body
            }).done(function(dataaaaaa) {
              document.getElementById('loading').style.display = 'none';
              document.getElementById('ModalTitle').style.display = 'block';
              if (dataaaaaa == 'No Model Found'){
                
                document.getElementById('predictButton').innerHTML = dataaaaaa;
                document.getElementById('predictButton').className =  'btn btn-block btn-info disabled';
              }
              else{
                
                var classs = dataaaaaa.split('+')[0]
                var acc = dataaaaaa.split('+')[1]
                var algo = dataaaaaa.split('+')[2]
                var para = dataaaaaa.split('+')[3]
                if (classs = 'Fair'){
                  show_class = 'Fair / HOLD'
                }
                else if (classs = 'Under'){
                  show_class = 'Under / BUY'
                }
                else if (classs = 'Over'){
                  show_class = 'Over / SELL'
                }

                document.getElementById('predictButton').style.display = 'none';
                document.getElementById('modal_class_result').innerHTML = show_class;
                document.getElementById('modal_class_result').className = classs;
                document.getElementById('resultModal').style.display = 'block';
                document.getElementById('accuracy').innerHTML = acc;
                document.getElementById('algorithm').innerHTML = algo;
                document.getElementById('parameter').innerHTML = para;
              }
                
             alert('Process Is Done');;
            });
        }

    
        function createCharts(){
      var min_p_eps = selected_stock_details[0]['avg_p_eps'] - selected_stock_details[0]['stdev_p_eps']
      var max_p_eps = selected_stock_details[0]['avg_p_eps'] + selected_stock_details[0]['stdev_p_eps']
      
      var chart_P_EPS = new CanvasJS.Chart("chart_P_EPS", {
            animationEnabled: true,
            exportEnabled: true,
            width:750,
            title: {
                text: "Fundamental Ratio in " + selected_stock_details[0]['stockName']
            },
            axisX: {
                title: "Ratio"
            },
            axisY: {
                includeZero: false,
                title: "Ratio in %",
                interval: max_p_eps/4,
                suffix: "%",
                // prefix: "$"
            }, 
            data: [{
                type: "rangeBar",
                showInLegend: true,
                yValueFormatString: "###.0",
                indexLabel: "{y[#index]}",
                legendText: "Max & Min Ratio",
                toolTipContent: "<b>{label}</b>: <br> min : {y[0]} <br> current : {z} <br> max : {y[1]}",
                dataPoints: [
                    { x: 1, y:[min_p_eps, max_p_eps], label: "P/EPS", z: selected_stock_details[0]['P_EPS'] }
                ]
            }]
      });
      chart_P_EPS.render()

      var min_p_rps = selected_stock_details[0]['avg_p_rps'] - selected_stock_details[0]['stdev_p_rps']
      var max_p_rps = selected_stock_details[0]['avg_p_rps'] + selected_stock_details[0]['stdev_p_rps']

      var chart_P_RPS = new CanvasJS.Chart("chart_P_RPS", {
            animationEnabled: true,
            exportEnabled: true,
            width:720,
            title: {
                text: "Fundamental Ratio in " + selected_stock_details[0]['stockName']
            },
            axisX: {
                title: "Ratio"
            },
            axisY: {
                includeZero: false,
                title: "Ratio",
                interval: max_p_rps/2,
                suffix: "%",
                // prefix: "$"
            }, 
            data: [{
                type: "rangeBar",
                showInLegend: true,
                yValueFormatString: "###.00",
                indexLabel: "{y[#index]}",
                legendText: "Max & Min Ratio",
                toolTipContent: "<b>{label}</b>: <br> min : {y[0]} <br> current : {z} <br> max : {y[1]}",
                dataPoints: [
                    { x: 1, y:[min_p_rps, max_p_rps], label: "P/RPS" ,z : selected_stock_details[0]['P_RPS']},
                ]
            }]
        });

        chart_P_RPS.render()

        var min_p_naps = selected_stock_details[0]['avg_p_naps'] - selected_stock_details[0]['stdev_p_naps']
        var max_p_naps = selected_stock_details[0]['avg_p_naps'] + selected_stock_details[0]['stdev_p_naps']
        
        var chart_P_NAPS = new CanvasJS.Chart("chart_P_NAPS", {
            animationEnabled: true,
            exportEnabled: true,
            width:720,
            title: {
                text: "Fundamental Ratio in " + selected_stock_details[0]['stockName']
            },
            axisX: {
                title: "Ratio"
            },
            axisY: {
                includeZero: false,
                title: "Ratio",
                interval: 0.5,
                suffix: "%",
                // prefix: "$"
            }, 
            data: [{
                type: "rangeBar",
                showInLegend: true,
                yValueFormatString: "###.00",
                indexLabel: "{y[#index]}",
                legendText: "Max & Min Ratio",
                toolTipContent: "<b>{label}</b>: <br> min : {y[0]} <br> current : {z} <br> max : {y[1]}",
                dataPoints: [
                    { x: 1, y:[min_p_naps, max_p_naps], label: "P/NAPS" ,z : selected_stock_details[0]['P_NAPS']},
                ]
            }]
        });

        chart_P_NAPS.render()
    
    }
    
    function minusMonth(date, month) {
      var result = new Date(date);
      result.setMonth(result.getMonth() - month)
      return result.toISOString().split('T')[0];
    }

    function validate(){
      var dt = new Date(document.getElementById('theDate').value);
      
      var selected_date_price = 0;

      month = dt.getMonth()
      year = dt.getFullYear()

      quarter = getQuarter(month, quarter_type)
      selected_date = dt.toISOString().split('T')[0];
      selected_day = dt.getDay()

      if (selected_day == 0 || selected_day == 6){
        alert('Please Choose Any Other Date EXCEPT WEEKEND')
      }
      else{
        for (var j = 0; j < historical_data.length; j++) {
        if (historical_data[j]['Date'] == selected_date){
          selected_date_price = historical_data[j]['Close']
          }
      }

        if (selected_date_price != 0){
          twenty_MA = diffPerc(20,selected_date)
        fifty_MA = diffPerc(50,selected_date)
        hundred_MA = diffPerc(100,selected_date)

        var quarter_data = <?php echo json_encode($quarterdetails);?>;
        for (var z = 0; z < quarter_data.length; z++){
          hist_quarter_date = new Date(quarter_data[z]['quarter'])
          hist_quarter_month = quarter_data[z]['quarter'].split('-')[1]
          hist_quarter = getQuarter(hist_quarter_month, quarter_type)
          start_date = minusMonth(hist_quarter_date,3)
          
          if  ((Date.parse(selected_date) <= Date.parse(hist_quarter_date) && Date.parse(selected_date) >= Date.parse(start_date))) {
            quarter_year = quarter_data[z]['quarter'].split('-')[0].slice(-2)
            selected_quarter_index = z
            break;
          }
        }

        class_by_statis_model = produceStatiscalModel(selected_date_price,selected_quarter_index)


        var table = document.getElementById("validateTable");
        if (first_entry == true){
          table.deleteRow(1)
          var row = table.insertRow(-1);
          first_entry = false
        }else{
          var row = table.insertRow(-1);
        }
      
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);

        
        // Add some text to the new cells:
        cell1.innerHTML = quarter + '/' + quarter_year;
        cell2.innerHTML = selected_date;
        cell3.innerHTML = parseFloat(selected_date_price).toFixed(2);
        cell4.innerHTML = parseInt(twenty_MA);
        cell5.innerHTML = parseInt(fifty_MA);
        cell6.innerHTML = parseInt(hundred_MA);
        cell7.innerHTML = class_by_statis_model;
        }
        else{
          alert('Missing Stock Date for Selected Day !!! Please Repick Another One')
        }
        
      }

      
    }

    function diffPerc (MA,selectedDate){

      var start_total_closed = 0;
      var end_total_closed = 0;
      // var diff_perc = 0;

      for (var j = 0; j < historical_data.length; j++) {
        if (historical_data[j]['Date'] == selectedDate){
          for (var i=j; i<j+MA;i++){
            start_total_closed += parseFloat(historical_data[i]['Close']);
          }
          for (var z=i-1; z<i+MA-1;z++){
            end_total_closed += parseFloat(historical_data[z]['Close']);
            console.log(historical_data[z]['Date'])
            console.log(end_total_closed)
          }
          break;
        }
      }
      
      start_total_closed = start_total_closed / MA
      end_total_closed = end_total_closed / MA
      diff_perc = (end_total_closed - start_total_closed ) / start_total_closed * 100

      return diff_perc
    }

    function produceStatiscalModel(price,index){
      // console.log(index)
      p_eps_list = new Array()
      p_naps_list = new Array()
      p_rps_list = new Array()

      for (a=index;a<quarter_data_list.length;a++){
        if (a == index){
          curr_eps = quarter_data_list[a]['EPS']
          curr_rps = quarter_data_list[a]['RPS']
          curr_naps = quarter_data_list[a]['NAPS']
        }
        ave_price = 0
        ave_price = (parseFloat(quarter_data_list[a]['EOQ_PRICE']) + parseFloat(quarter_data_list[a]['ANN_PRICE'])) / 2
        p_eps_list.push(parseFloat(ave_price / parseFloat(quarter_data_list[a]['EPS']) * 100))
        p_rps_list.push(parseFloat(ave_price / parseFloat(quarter_data_list[a]['RPS']) * 100))
        if (quarter_data_list[a]['NAPS'] != 0)
          {
            p_naps_list.push(parseFloat(ave_price / parseFloat(quarter_data_list[a]['NAPS'])))      
          }
        }

        eps_n = p_eps_list.length
        eps_mean = p_eps_list.reduce((a,b) => a+b)/eps_n
        std_eps =  Math.sqrt(p_eps_list.map(x => Math.pow(x-eps_mean,2)).reduce((a,b) => a+b)/eps_n)
        top_eps = eps_mean + std_eps
        btm_eps = eps_mean - std_eps

        rps_n = p_rps_list.length
        rps_mean = p_rps_list.reduce((a,b) => a+b)/rps_n
        std_rps =  Math.sqrt(p_rps_list.map(x => Math.pow(x-rps_mean,2)).reduce((a,b) => a+b)/rps_n)
        top_rps = rps_mean + std_rps
        btm_rps = rps_mean - std_rps

        naps_n = p_naps_list.length
        naps_mean = p_naps_list.reduce((a,b) => a+b)/naps_n
        std_naps =  Math.sqrt(p_naps_list.map(x => Math.pow(x-naps_mean,2)).reduce((a,b) => a+b)/naps_n)
        top_naps = naps_mean + std_naps
        btm_naps = naps_mean - std_naps

        sel_p_eps = ( price / curr_eps )* 100 
        sel_p_rps = ( price / curr_rps )* 100 
        sel_p_naps = ( price / curr_naps )

        if (sel_p_eps > btm_eps &&  sel_p_eps <top_eps){
          eps_class ='Fair'
        }
        else if ( sel_p_eps > top_eps){
          eps_class ='Over'
        }
        else if (sel_p_eps < btm_eps){
          eps_class ='Under'
        }

        if (sel_p_rps > btm_rps &&  sel_p_rps <top_rps){
          rps_class ='Fair'
        }
        else if ( sel_p_rps > top_rps){
          rps_class ='Over'
        }
        else if (sel_p_rps < btm_rps){
          rps_class ='Under'
        }

        if (sel_p_naps > btm_naps &&  sel_p_naps <top_naps){
          naps_class ='Fair'
        }
        else if ( sel_p_naps > top_naps){
          naps_class ='Over'
        }
        else if (sel_p_naps < btm_naps){
          naps_class ='Under'
        }

        class_list = new Array()
        class_list.push (eps_class)
        class_list.push (rps_class)
        class_list.push (naps_class)
        overall_class = getOverAllClass(class_list)
        console.log(class_list)
        console.log(overall_class)

        return overall_class
        
    }

    function getOverAllClass (arr){
      var counts = {};
      for (var i = 0; i < arr.length; i++) {
        var num = arr[i];
        counts[num] = counts[num] ? counts[num] + 1 : 1;
      }
      
      if (counts['Over'] >=1){
        overall_class = 'Over'
        
      }
      else if(counts['Under'] == 3 || (counts['Under'] == 2 && counts ['Fair'] == 1)) 
      {
        overall_class = 'Under'
      }
      else if (counts['Fair'] == 2 && counts['Under'] == 1){
        overall_class = 'Fair/Under'
      }
      else
      {
        overall_class = 'Fair'
      }
      return overall_class
    }

    function getQuarter(month, quarter_type){
      var quarter = "";

      if (quarter_type == 1){
        if ( month == 0 || month == 1 || month == 2 ){
        quarter = "Q1";
        }
        else if( month == 3 || month == 4 || month == 5 ){
          quarter = "Q2";
        }
        else if( month == 6 || month == 7 || month == 8 ){
          quarter = "Q3";
        }
        else if( month == 9 || month == 10 || month == 11 ){
          quarter = "Q4";
        }
      }
      else if(quarter_type == 2)
      {
        if ( month == 11 || month == 0 || month == 1 ){
        quarter = "Q1";
        }
        else if( month == 2 || month == 3 || month == 4 ){
          quarter = "Q2";
        }
        else if( month == 5 || month == 6 || month == 7 ){
          quarter = "Q3";
        }
        else if( month == 8 || month == 9 || month == 10 ){
          quarter = "Q4";
        }
      }

      return quarter
    }

    function price_to_sell_buy(){
      // var price = selected_stock_details[0]['Price']
      var buy_price, sell_price
      var price_list = new Array()
      var sell_price_list = new Array()
      var buy_price_list = new Array()

      var EPS = selected_stock_details[0]['EPS']
      var RPS = selected_stock_details[0]['RPS']
      var NAPS = selected_stock_details[0]['NAPS']
      var min_p_eps = selected_stock_details[0]['avg_p_eps'] - selected_stock_details[0]['stdev_p_eps']
      var max_p_eps = selected_stock_details[0]['avg_p_eps'] + selected_stock_details[0]['stdev_p_eps']
      var max_p_rps = selected_stock_details[0]['avg_p_rps'] + selected_stock_details[0]['stdev_p_rps']
      var min_p_rps = selected_stock_details[0]['avg_p_rps'] - selected_stock_details[0]['stdev_p_rps']
      var min_p_naps = selected_stock_details[0]['avg_p_naps'] - selected_stock_details[0]['stdev_p_naps']
      var max_p_naps = selected_stock_details[0]['avg_p_naps'] + selected_stock_details[0]['stdev_p_naps']
     
      if (selected_stock_details[0]['class'] == 'Under'){
        price_eps = parseFloat(max_p_eps * EPS / 100 ) .toFixed(2)
        // price_list.push(price_eps)
        price_naps = parseFloat(max_p_naps * NAPS).toFixed(2)
        price_list.push(price_naps)
        price_rps = parseFloat(max_p_rps * RPS / 100 ) .toFixed(2)
        price_list.push(price_rps)

        price_list = price_list.filter(function(x){ return x >0 })
        sell_price = Math.max.apply(Math, price_list)
      }
      else if(selected_stock_details[0]['class'] == 'Over'){
        price_eps = parseFloat(min_p_eps * EPS / 100 ) .toFixed(2)
        // price_list.push(price_eps)
        price_naps = parseFloat(min_p_naps * NAPS).toFixed(2)
        price_list.push(price_naps)
        price_rps = parseFloat(min_p_rps * RPS / 100 ) .toFixed(2)
        price_list.push(price_rps)
        
        price_list = price_list.filter(function(x){ return x >0 })
        buy_price = Math.min.apply(Math, price_list)
      }
      else{
        price_eps = parseFloat(max_p_eps * EPS / 100 ) .toFixed(2)
        // sell_price_list.push(price_eps)
        price_naps = parseFloat(max_p_naps * NAPS).toFixed(2)
        sell_price_list.push(price_naps)
        price_rps = parseFloat(max_p_rps * RPS / 100 ) .toFixed(2)
        sell_price_list.push(price_rps)
        console.log(sell_price_list)
        sell_price_list = sell_price_list.filter(function(x){ return x >0 })
        console.log(sell_price_list)
        sell_price = Math.max.apply(Math, sell_price_list)
        
        price_eps = parseFloat(min_p_eps * EPS / 100 ) .toFixed(2)
        buy_price_list.push(price_eps)
        price_naps = parseFloat(min_p_naps * NAPS).toFixed(2)
        buy_price_list.push(price_naps)
        price_rps = parseFloat(min_p_rps * RPS / 100 ) .toFixed(2)
        buy_price_list.push(price_rps)

        console.log(buy_price_list)
        buy_price_list = buy_price_list.filter(function(x){ return x >0 })
        buy_price = Math.min.apply(Math, buy_price_list)
        
      }
      if (buy_price && sell_price){
        document.getElementById("to_buy").innerHTML = buy_price + '   <  '
        document.getElementById("to_sell").innerHTML = ' > ' + sell_price 
      }
      else if (buy_price){
        document.getElementById("to_buy").innerHTML = buy_price + '   <  '
        document.getElementById("to_sell").innerHTML = 'SELL NOW !!!'
      }
      else if (sell_price){
        document.getElementById("to_sell").innerHTML = ' > ' + sell_price 
        document.getElementById("to_buy").innerHTML = 'BUY NOW !!!'
      }
      // console.log('buy when price < ' + buy_price)
      // console.log('sell when price > ' +sell_price)
    }

    
    
</script>



<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="dist/js/demo.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>

<!-- PAGE SCRIPTS -->
<script src="dist/js/pages/dashboard2.js"></script>
</body>
</html>
